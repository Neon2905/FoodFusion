@props(['name' => 'tags', 'placeholder' => 'Add a tag'])

<div x-data="{
    tags: ['Someting', 'Another'],
    inputValue: '',
    addFromInput() {
        const v = (this.inputValue || '').trim();
        if (!v) return;
        if (!this.tags.includes(v)) this.tags.push(v);
        this.inputValue = '';
    },
    remove(i) {
        this.tags.splice(i, 1);
    },
    onBackspace(e) {
        if (this.inputValue === '') {
            this.tags.pop();
        }
    }
}" x-cloak class="flex items-center gap-2 flex-wrap p-2 border rounded-md">
    <template x-for="(t, i) in tags" :key="i">
        <div class="tag flex items-center gap-2 px-3 py-1 bg-gray text-sm rounded-full">
            <span x-text="t"></span>
            <button type="button" @click.prevent="remove(i)" class="text-sm text-red-600">×</button>
            <input type="hidden" :name="`{{ $name }}[${i}]`" :value="t" />
        </div>
    </template>

    <input x-ref="input" @keydown.space.prevent="addFromInput()" @keydown.enter.prevent="addFromInput()"
        @keydown.backspace="onBackspace($event)" x-model="inputValue" placeholder="{{ $placeholder }}"
        class="flex-1 min-w-[120px] bg-transparent outline-none px-2 py-1" />

    <span x-model="tags">tags</span>
</div>
