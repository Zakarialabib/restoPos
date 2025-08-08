<template>
  <div class="code-tabs">
    <div class="tabs-header">
      <button
        v-for="(tab, index) in tabs"
        :key="tab.label"
        :class="['tab-button', { active: activeTab === index }]"
        @click="setActiveTab(index)"
      >
        <span class="tab-icon">{{ getLanguageIcon(tab.language) }}</span>
        <span class="tab-label">{{ tab.label }}</span>
      </button>
    </div>
    
    <div class="tabs-content">
      <div
        v-for="(tab, index) in tabs"
        :key="index"
        v-show="activeTab === index"
        class="tab-content"
      >
        <div class="code-header">
          <span class="language-label">{{ tab.language }}</span>
          <button
            class="copy-button"
            @click="copyCode(tab.code)"
            title="Copy code"
          >
            <span class="copy-icon">📋</span>
            Copy
          </button>
        </div>
        <pre class="code-block"><code :class="`language-${tab.language.toLowerCase()}`">{{ tab.code }}</code></pre>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, onMounted } from 'vue'

const props = defineProps({
  tabs: {
    type: Array,
    required: true,
    validator: (tabs) => {
      return tabs.every(tab => 
        tab.hasOwnProperty('label') && 
        tab.hasOwnProperty('language') && 
        tab.hasOwnProperty('code')
      )
    }
  },
  defaultTab: {
    type: Number,
    default: 0
  }
})

const activeTab = ref(props.defaultTab)

const setActiveTab = (index) => {
  activeTab.value = index
}

const getLanguageIcon = (language) => {
  const icons = {
    javascript: '🟨',
    typescript: '🔷',
    python: '🐍',
    php: '🐘',
    java: '☕',
    csharp: '🔷',
    go: '🐹',
    rust: '🦀',
    ruby: '💎',
    swift: '🦉',
    kotlin: '🎯',
    dart: '🎯',
    bash: '💻',
    shell: '💻',
    curl: '🌐',
    json: '📄',
    xml: '📄',
    yaml: '📄',
    sql: '🗃️',
    html: '🌐',
    css: '🎨'
  }
  return icons[language.toLowerCase()] || '📝'
}

const copyCode = (code) => {
  navigator.clipboard.writeText(code).then(() => {
    // Show success feedback
    const toast = document.createElement('div')
    toast.className = 'copy-toast'
    toast.textContent = 'Code copied to clipboard!'
    document.body.appendChild(toast)
    
    setTimeout(() => {
      if (document.body.contains(toast)) {
        document.body.removeChild(toast)
      }
    }, 2000)
  }).catch(err => {
    console.error('Failed to copy code: ', err)
    // Show error feedback
    const toast = document.createElement('div')
    toast.className = 'copy-toast error'
    toast.textContent = 'Failed to copy code'
    document.body.appendChild(toast)
    
    setTimeout(() => {
      if (document.body.contains(toast)) {
        document.body.removeChild(toast)
      }
    }, 2000)
  })
}

// Initialize syntax highlighting if available
onMounted(() => {
  if (typeof window !== 'undefined' && window.Prism) {
    window.Prism.highlightAll()
  }
})
</script>

<style scoped>
.code-tabs {
  border: 1px solid var(--vp-c-border);
  border-radius: 12px;
  overflow: hidden;
  margin: 16px 0;
  background: var(--vp-c-bg-soft);
}

.tabs-header {
  display: flex;
  background: var(--vp-c-bg-mute);
  border-bottom: 1px solid var(--vp-c-border);
  overflow-x: auto;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.tabs-header::-webkit-scrollbar {
  display: none;
}

.tab-button {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 12px 16px;
  border: none;
  background: transparent;
  color: var(--vp-c-text-2);
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
  border-bottom: 2px solid transparent;
}

.tab-button:hover {
  color: var(--vp-c-text-1);
  background: var(--vp-c-bg-soft);
}

.tab-button.active {
  color: var(--vp-c-brand-1);
  background: var(--vp-c-bg-soft);
  border-bottom-color: var(--vp-c-brand-1);
}

.tab-icon {
  font-size: 16px;
}

.tab-label {
  font-family: 'Inter', sans-serif;
}

.tabs-content {
  position: relative;
}

.tab-content {
  background: var(--vp-c-bg-alt);
}

.code-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  background: var(--vp-c-bg-mute);
  border-bottom: 1px solid var(--vp-c-border);
}

.language-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--vp-c-text-2);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: 'JetBrains Mono', monospace;
}

.copy-button {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 6px 10px;
  border: 1px solid var(--vp-c-border);
  border-radius: 6px;
  background: var(--vp-c-bg-soft);
  color: var(--vp-c-text-2);
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.copy-button:hover {
  background: var(--vp-c-brand-1);
  color: white;
  border-color: var(--vp-c-brand-1);
}

.copy-icon {
  font-size: 12px;
}

.code-block {
  margin: 0;
  padding: 20px;
  overflow-x: auto;
  font-family: 'JetBrains Mono', 'Fira Code', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
  font-size: 14px;
  line-height: 1.6;
  background: var(--vp-c-bg-alt);
  color: var(--vp-c-text-1);
}

.code-block code {
  background: none;
  padding: 0;
  font-size: inherit;
  color: inherit;
  font-family: inherit;
}

/* Syntax highlighting support */
.code-block .token.comment,
.code-block .token.prolog,
.code-block .token.doctype,
.code-block .token.cdata {
  color: #6a737d;
}

.code-block .token.punctuation {
  color: #586069;
}

.code-block .token.property,
.code-block .token.tag,
.code-block .token.boolean,
.code-block .token.number,
.code-block .token.constant,
.code-block .token.symbol,
.code-block .token.deleted {
  color: #005cc5;
}

.code-block .token.selector,
.code-block .token.attr-name,
.code-block .token.string,
.code-block .token.char,
.code-block .token.builtin,
.code-block .token.inserted {
  color: #032f62;
}

.code-block .token.operator,
.code-block .token.entity,
.code-block .token.url,
.code-block .language-css .token.string,
.code-block .style .token.string {
  color: #d73a49;
}

.code-block .token.atrule,
.code-block .token.attr-value,
.code-block .token.keyword {
  color: #d73a49;
}

.code-block .token.function,
.code-block .token.class-name {
  color: #6f42c1;
}

.code-block .token.regex,
.code-block .token.important,
.code-block .token.variable {
  color: #e36209;
}

/* Dark mode syntax highlighting */
.dark .code-block .token.comment,
.dark .code-block .token.prolog,
.dark .code-block .token.doctype,
.dark .code-block .token.cdata {
  color: #8b949e;
}

.dark .code-block .token.punctuation {
  color: #c9d1d9;
}

.dark .code-block .token.property,
.dark .code-block .token.tag,
.dark .code-block .token.boolean,
.dark .code-block .token.number,
.dark .code-block .token.constant,
.dark .code-block .token.symbol,
.dark .code-block .token.deleted {
  color: #79c0ff;
}

.dark .code-block .token.selector,
.dark .code-block .token.attr-name,
.dark .code-block .token.string,
.dark .code-block .token.char,
.dark .code-block .token.builtin,
.dark .code-block .token.inserted {
  color: #a5d6ff;
}

.dark .code-block .token.operator,
.dark .code-block .token.entity,
.dark .code-block .token.url,
.dark .code-block .language-css .token.string,
.dark .code-block .style .token.string {
  color: #ff7b72;
}

.dark .code-block .token.atrule,
.dark .code-block .token.attr-value,
.dark .code-block .token.keyword {
  color: #ff7b72;
}

.dark .code-block .token.function,
.dark .code-block .token.class-name {
  color: #d2a8ff;
}

.dark .code-block .token.regex,
.dark .code-block .token.important,
.dark .code-block .token.variable {
  color: #ffa657;
}

/* Responsive design */
@media (max-width: 768px) {
  .tabs-header {
    padding: 0;
  }
  
  .tab-button {
    padding: 10px 12px;
    font-size: 13px;
  }
  
  .code-header {
    padding: 10px 12px;
  }
  
  .code-block {
    padding: 16px 12px;
    font-size: 13px;
  }
  
  .copy-button {
    padding: 4px 8px;
    font-size: 11px;
  }
}

/* Focus styles for accessibility */
.tab-button:focus-visible,
.copy-button:focus-visible {
  outline: 2px solid var(--vp-c-brand-1);
  outline-offset: 2px;
}

/* Animation for tab switching */
.tab-content {
  animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Disable animations for reduced motion */
@media (prefers-reduced-motion: reduce) {
  .tab-content {
    animation: none;
  }
}
</style>