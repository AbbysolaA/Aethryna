<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VolunteerDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * The onboarding pack, managed from the admin.
 *
 * Uploads go to the 'local' disk (storage/app/private) under a generated name,
 * so the original filename never appears in a URL and nothing is guessable.
 * The welcome email lists whatever is active here, in sort order.
 */
class VolunteerDocumentAdminController extends Controller
{
    /** Extensions we accept. Office documents and PDFs, nothing executable. */
    private const ALLOWED = 'pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,rtf,txt';

    /** Max upload in kilobytes. */
    private const MAX_KB = 10240;

    public function index(): View
    {
        return view('admin.volunteer-documents.index', [
            'documents' => VolunteerDocument::inPackOrder()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label'      => ['required', 'string', 'max:255'],
            'note'       => ['nullable', 'string', 'max:255'],
            'file'       => ['required', 'file', 'mimes:' . self::ALLOWED, 'max:' . self::MAX_KB],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ], [
            'file.mimes' => 'That file type is not accepted. Use a PDF, Word, Excel or PowerPoint file.',
            'file.max'   => 'That file is over 10MB. Compress it or link to it instead.',
        ]);

        $file = $request->file('file');

        // Generated name, original kept in the row. Nothing about the stored
        // path reveals what the file is or lets it be guessed.
        $path = $file->store('volunteer-documents', VolunteerDocument::DISK);

        VolunteerDocument::create([
            'label'         => $validated['label'],
            'note'          => $validated['note'] ?? null,
            'path'          => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime'          => $file->getClientMimeType(),
            'size'          => $file->getSize(),
            'sort_order'    => $validated['sort_order'] ?? $this->nextSortOrder(),
            'is_active'     => true,
        ]);

        return redirect()
            ->route('admin.volunteer-documents.index')
            ->with('status', 'Document uploaded. It will be listed in the welcome email.');
    }

    /**
     * Edit the label, note, order and active flag. Optionally replace the file
     * itself, which deletes the old one rather than leaving it orphaned.
     */
    public function update(Request $request, VolunteerDocument $document): RedirectResponse
    {
        $validated = $request->validate([
            'label'      => ['required', 'string', 'max:255'],
            'note'       => ['nullable', 'string', 'max:255'],
            'file'       => ['nullable', 'file', 'mimes:' . self::ALLOWED, 'max:' . self::MAX_KB],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active'  => ['nullable', 'boolean'],
        ], [
            'file.mimes' => 'That file type is not accepted. Use a PDF, Word, Excel or PowerPoint file.',
            'file.max'   => 'That file is over 10MB. Compress it or link to it instead.',
        ]);

        $attributes = [
            'label'      => $validated['label'],
            'note'       => $validated['note'] ?? null,
            'sort_order' => $validated['sort_order'] ?? $document->sort_order,
            'is_active'  => $request->boolean('is_active'),
        ];

        if ($request->hasFile('file')) {
            $old  = $document->path;
            $file = $request->file('file');

            $attributes['path']          = $file->store('volunteer-documents', VolunteerDocument::DISK);
            $attributes['original_name'] = $file->getClientOriginalName();
            $attributes['mime']          = $file->getClientMimeType();
            $attributes['size']          = $file->getSize();

            // Only after the replacement is safely stored.
            Storage::disk(VolunteerDocument::DISK)->delete($old);
        }

        $document->update($attributes);

        return redirect()
            ->route('admin.volunteer-documents.index')
            ->with('status', 'Document updated.');
    }

    /**
     * Removes the row and the file. The model's deleting event handles the
     * file, so there is no way to delete one without the other.
     */
    public function destroy(VolunteerDocument $document): RedirectResponse
    {
        $document->delete();

        return redirect()
            ->route('admin.volunteer-documents.index')
            ->with('status', 'Document deleted.');
    }

    private function nextSortOrder(): int
    {
        return (int) VolunteerDocument::max('sort_order') + 10;
    }
}
