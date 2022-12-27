@props([
    'team'
])


<li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
    <form method="POST" action="{{ route('current-team.update') }}" x-data>
        @method('PUT')
        @csrf

        <!-- Hidden Team ID -->
        <input type="hidden" name="team_id" value="{{ $team->id }}">

        <a class="flex w-full items-center justify-between space-x-6 p-6" href="#" x-on:click.prevent="$root.submit();">
            <div class="flex-1 truncate">
                <div class="flex items-center space-x-3">
                    @if (Auth::user()->isCurrentTeam($team))
                        <svg class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                    <h3 class="truncate text-sm font-medium text-gray-900">
                        {{ $team->name }}
                    </h3>
                </div>
            </div>
        </a>
    </form>
</li>
