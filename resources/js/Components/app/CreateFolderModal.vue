<template>
    <modal :show="modelValue" @show="onShow" max-width="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Create New Folder
            </h2>
            <div class="mt-6">
                <InputLabel for="folderName" value="Folder Name" class="sr-only" />
                <TextInput
                    type="text"
                    ref="folderNameInput"
                    id="folderName"
                    class="mt-1 block w-full"
                    :class="form.errors.name ?
                        'border-red-500 focus:border-red-500 focus:ring-red-500' :
                        ''"
                    placeholder="Folder Name"
                    v-model="form.name"
                    @keyup.enter="createFolder"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                <PrimaryButton
                    @click="createFolder"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Submit
                </PrimaryButton>
            </div>
        </div>
    </modal>
</template>

<script setup>
// Imports
import { nextTick, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

// Uses
const form = useForm({
    name: ''
});

// Refs
const folderNameInput = ref(null);

// Props & Emit
const { modelValue } = defineProps({
    modelValue: Boolean
});

const emit = defineEmits(['update:modelValue']);

// Methods
function onShow () {
    nextTick(() => folderNameInput.value.focus());
}

function createFolder() {
    form.post(route('folder.create'), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            form.reset();
            // Show success notification
        },
        onError: () => folderNameInput.value.focus()
    });
}

function closeModal() {
    emit('update:modelValue');
    form.clearErrors();
    form.reset();
}

</script>

<style lang="scss" scoped>

</style>
