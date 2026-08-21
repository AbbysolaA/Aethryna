<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Posting and maintaining the volunteer positions we recruit for.
 *
 * Roles live in the database rather than a config array so a new position can
 * go up without a deploy. Mentor is one of these: setting grants_access to
 * 'mentor' is what makes an accepted offer open the /mentor area.
 */
class VolunteerRoleAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.volunteer-roles.index', [
            'roles' => VolunteerRole::withCount('engagements')
                ->orderByDesc('is_open')
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.volunteer-roles.form', [
            'role' => new VolunteerRole(['is_open' => true, 'requires_nda' => true, 'engagement_type' => 'volunteer']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['slug'] = $this->uniqueSlug($validated['title']);

        $role = VolunteerRole::create($validated);

        return redirect()
            ->route('admin.volunteer-roles.index')
            ->with('status', $role->isPaid()
                ? 'Role created. It is now listed at /careers.'
                : 'Role created. It is now listed on /volunteer/apply.');
    }

    public function edit(VolunteerRole $role): View
    {
        return view('admin.volunteer-roles.form', ['role' => $role]);
    }

    public function update(Request $request, VolunteerRole $role): RedirectResponse
    {
        // The slug is left alone on edit. Changing it would break any link
        // already shared pointing at this role.
        $role->update($this->validated($request, $role));

        return redirect()
            ->route('admin.volunteer-roles.index')
            ->with('status', 'Role updated.');
    }

    /**
     * Deletion is refused once anyone has been offered the role, because the
     * engagement records point at it and would lose the title they were
     * offered under. Close it instead, which hides it from the application
     * form and leaves history intact.
     */
    public function destroy(VolunteerRole $role): RedirectResponse
    {
        if ($role->engagements()->exists()) {
            return redirect()
                ->route('admin.volunteer-roles.index')
                ->with('error', 'That role has engagements against it, so it cannot be deleted. Close it instead.');
        }

        // Same rule for job applications: deleting the role would leave the
        // people who applied for it pointing at nothing.
        if ($role->jobApplications()->exists()) {
            return redirect()
                ->route('admin.volunteer-roles.index')
                ->with('error', 'People have applied for that role, so it cannot be deleted. Close it instead.');
        }

        $role->delete();

        return redirect()
            ->route('admin.volunteer-roles.index')
            ->with('status', 'Role deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?VolunteerRole $role = null): array
    {
        $validated = $request->validate([
            'title'         => [
                'required', 'string', 'max:255',
                Rule::unique('volunteer_roles', 'title')->ignore($role?->id),
            ],
            'summary'       => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:4000'],
            'grants_access' => ['required', Rule::in(['volunteer', 'mentor'])],
            'requires_dbs'  => ['nullable', 'boolean'],
            'requires_nda'  => ['nullable', 'boolean'],
            'is_open'       => ['nullable', 'boolean'],

            // Paid roles. All optional: a volunteer role leaves every one of
            // them blank, and a vacancy can be posted before the salary is
            // settled without the form standing in the way.
            'engagement_type'    => ['required', Rule::in(['volunteer', 'employee', 'contractor'])],
            'compensation'       => ['nullable', 'string', 'max:255'],
            'employment_basis'   => ['nullable', 'string', 'max:255'],
            'location'           => ['nullable', 'string', 'max:255'],
            'reports_to'         => ['nullable', 'string', 'max:255'],
            'apply_email'        => ['nullable', 'email', 'max:255'],
            'apply_instructions' => ['nullable', 'string', 'max:1000'],
            'closes_at'          => ['nullable', 'date'],
        ], [
            'title.unique'    => 'There is already a role with that title.',
            'summary.required' => 'The summary is the one line shown on the application page.',
            'apply_email.email' => 'Applications need somewhere to go: use a real email address or leave it blank.',
        ]);

        // Unchecked boxes are absent from the request rather than false.
        $validated['requires_dbs'] = $request->boolean('requires_dbs');
        $validated['requires_nda'] = $request->boolean('requires_nda');
        $validated['is_open']      = $request->boolean('is_open');

        return $validated;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $n    = 2;

        while (VolunteerRole::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
