<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VolunteerRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The route from "this position is open" to "this person holds it".
 *
 * Positions are assigned from the volunteers roster, not from Staff and
 * access, and the founder went looking in the wrong place because nothing
 * said so. The positions list now carries the shortcut and the staff screen
 * carries the signpost.
 */
class AdminAssignShortcutTest extends TestCase
{
    use RefreshDatabase;

    private function role(array $overrides = []): VolunteerRole
    {
        return VolunteerRole::create(array_merge([
            'title'           => 'Executive Assistant & Content Lead',
            'slug'            => 'executive-assistant-content-lead',
            'engagement_type' => 'volunteer',
            'summary'         => 'A broad role across operations and content.',
            'description'     => 'Working closely with the Founder.',
            'grants_access'   => 'volunteer',
            'is_open'         => true,
        ], $overrides));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_open_positions_offer_an_assign_someone_shortcut(): void
    {
        $open = $this->role();
        $closed = $this->role([
            'title' => 'Closed role', 'slug' => 'closed-role', 'is_open' => false,
        ]);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.volunteer-roles.index'))
            ->assertOk()
            ->assertSee('Assign someone')
            ->assertSee(route('admin.volunteers.create', ['role' => $open->id]), false);

        // A closed role takes no new people, so no shortcut.
        $response->assertDontSee(route('admin.volunteers.create', ['role' => $closed->id]), false);
    }

    public function test_the_shortcut_lands_with_the_role_preselected(): void
    {
        $role = $this->role();
        $other = $this->role(['title' => 'Another role', 'slug' => 'another-role']);

        $html = $this->actingAs($this->admin())
            ->get(route('admin.volunteers.create', ['role' => $role->id]))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('value="'.$role->id.'" selected', $html);
        $this->assertStringNotContainsString('value="'.$other->id.'" selected', $html);
    }

    public function test_the_offer_form_preselects_nothing_without_the_shortcut(): void
    {
        $this->role();

        $this->actingAs($this->admin())
            ->get(route('admin.volunteers.create'))
            ->assertOk()
            ->assertDontSee('" selected', false);
    }

    public function test_staff_and_access_points_position_assignment_at_volunteers(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee('assigning someone to a volunteer')
            ->assertSee('Volunteers</a> instead', false)
            ->assertSee(route('admin.volunteers.index'), false);
    }
}
