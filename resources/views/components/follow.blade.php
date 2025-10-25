@props(['profile', 'isFollowing' => false])

<div x-data="followButton()" x-cloak>
    <button class="flex-center button h-10 rounded-full bg-light-gray w-auto px-4" @click="toggle()">
        <h2 class="text-heading-lg" x-text="isFollowing ? 'Unfollow' : 'Follow'"></h2>
        <template x-if="!isLoading">
            <x-icons.user-plus x-show="!isFollowing" />
            <x-heroicon-o-user-minus x-show="isFollowing" /> {{-- TODO: Icon might be broken. Find a new package. --}}
        </template>
        <template x-if="isLoading">
            <x-loader></x-loader>
        </template>
    </button>
</div>

<script>
    function followButton() {
        return {
            isFollowing: @js($isFollowing),
            isLoading: false,
            async toggle() {
                try {
                    this.isLoading = true;
                    await axios.post(@json(route('follow.store', $profile->username)));
                    this.isFollowing = !this.isFollowing;
                } catch (error) {
                    console.error('There was an error: ', error);
                } finally {
                    this.isLoading = false;
                }
            }
        }
    }
</script>
