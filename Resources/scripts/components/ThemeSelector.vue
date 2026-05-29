<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <RadioGroup v-model="selectedTheme">
    <RadioGroupLabel class="text-base font-medium text-gray-900">
      Select a Theme
    </RadioGroupLabel>

    <div class="mt-2 grid grid-cols-2 gap-y-6 md:grid-cols-3 gap-x-4">
      <RadioGroupOption
        v-for="theme in themes"
        :key="theme.key"
        v-slot="{ checked, active }"
        as="template"
        :value="theme"
      >
        <div
          :class="[
            checked ? 'border-transparent' : 'border-gray-300',
            active ? 'ring-2 ring-primary-500' : '',
            'relative bg-white border rounded-lg shadow-sm flex flex-col p-4 cursor-pointer focus:outline-none',
          ]"
        >
          <div class="flex w-full justify-between">
            <div class="flex flex-col">
              <RadioGroupLabel
                as="span"
                class="block text-sm font-medium text-gray-900"
              >
                {{ theme.name }}
              </RadioGroupLabel>
            </div>

            <CheckCircleIcon
              :class="[!checked ? 'invisible' : '', 'h-5 w-5 text-primary-600']"
              aria-hidden="true"
            />
          </div>
          <div class="flex w-full mt-3">
            <div
              v-for="color in theme.colors"
              :key="color"
              class="flex-1 h-8"
              :style="{ backgroundColor: color }"
            ></div>
          </div>

          <div
            :class="[
              active ? 'border' : 'border-2',
              checked ? 'border-primary-500' : 'border-transparent',
              'absolute -inset-px rounded-lg pointer-events-none',
            ]"
            aria-hidden="true"
          />
        </div>
      </RadioGroupOption>
    </div>
  </RadioGroup>
</template>

<script setup>
import { ref, watch } from 'vue'
import {
  RadioGroup,
  RadioGroupDescription,
  RadioGroupLabel,
  RadioGroupOption,
} from '@headlessui/vue'
import { CheckCircleIcon } from '@heroicons/vue/20/solid'
import themes from '~/scripts/themes'

const props = defineProps({
  modelValue: {
    type: String,
    required: true,
  },
  updateBodyTheme: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits(['update:modelValue'])

const selectedTheme = ref(null)

selectedTheme.value = themes.find((theme) => theme.key === props.modelValue)

if (!selectedTheme.value) {
  selectedTheme.value = themes[0]
  emit('update:modelValue', selectedTheme.value.key)
}

watch(selectedTheme, (val) => {
  toggleTheme(val.key)
  emit('update:modelValue', val.key)
})

function toggleTheme(theme) {
  if (!props.updateBodyTheme) {
    return
  }

  let className = document.body.className.match(/(^|\s)theme-\S+/g) || []

  if (className && className.length) {
    document.body.classList.remove(className[0].trim())

    document.body.className += ' theme-' + theme
  }
}
</script>
