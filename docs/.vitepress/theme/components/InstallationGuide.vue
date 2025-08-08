<template>
  <div class="installation-guide">
    <div class="guide-header">
      <h3 class="guide-title">
        <span class="title-icon">📦</span>
        {{ title || 'Installation Guide' }}
      </h3>
      <p class="guide-description" v-if="description">
        {{ description }}
      </p>
    </div>

    <div class="installation-methods">
      <div class="method-tabs">
        <button
          v-for="(method, index) in methods"
          :key="method.name"
          :class="['method-tab', { active: activeMethod === index }]"
          @click="setActiveMethod(index)"
        >
          <span class="method-icon">{{ getMethodIcon(method.type) }}</span>
          <span class="method-name">{{ method.name }}</span>
        </button>
      </div>

      <div class="method-content">
        <div
          v-for="(method, index) in methods"
          :key="index"
          v-show="activeMethod === index"
          class="method-panel"
        >
          <div class="method-info" v-if="method.description">
            <p class="method-description">{{ method.description }}</p>
          </div>

          <div class="prerequisites" v-if="method.prerequisites && method.prerequisites.length > 0">
            <h4 class="section-title">
              <span class="section-icon">⚠️</span>
              Prerequisites
            </h4>
            <ul class="prerequisites-list">
              <li v-for="prereq in method.prerequisites" :key="prereq" class="prerequisite-item">
                <span class="prereq-icon">•</span>
                {{ prereq }}
              </li>
            </ul>
          </div>

          <div class="installation-steps">
            <h4 class="section-title">
              <span class="section-icon">🚀</span>
              Installation Steps
            </h4>
            
            <div class="steps-container">
              <div
                v-for="(step, stepIndex) in method.steps"
                :key="stepIndex"
                class="step-item"
              >
                <div class="step-number">{{ stepIndex + 1 }}</div>
                <div class="step-content">
                  <h5 class="step-title" v-if="step.title">{{ step.title }}</h5>
                  <p class="step-description" v-if="step.description">{{ step.description }}</p>
                  
                  <div class="step-code" v-if="step.code">
                    <div class="code-header">
                      <span class="code-language">{{ step.language || 'bash' }}</span>
                      <button class="copy-btn" @click="copyCode(step.code)" title="Copy code">
                        <span class="copy-icon">📋</span>
                        Copy
                      </button>
                    </div>
                    <pre class="code-block"><code>{{ step.code }}</code></pre>
                  </div>
                  
                  <div class="step-note" v-if="step.note">
                    <span class="note-icon">💡</span>
                    <span class="note-text">{{ step.note }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="verification" v-if="method.verification">
            <h4 class="section-title">
              <span class="section-icon">✅</span>
              Verify Installation
            </h4>
            <div class="verification-content">
              <p class="verification-description">{{ method.verification.description }}</p>
              <div class="verification-code" v-if="method.verification.code">
                <div class="code-header">
                  <span class="code-language">{{ method.verification.language || 'bash' }}</span>
                  <button class="copy-btn" @click="copyCode(method.verification.code)" title="Copy code">
                    <span class="copy-icon">📋</span>
                    Copy
                  </button>
                </div>
                <pre class="code-block"><code>{{ method.verification.code }}</code></pre>
              </div>
            </div>
          </div>

          <div class="troubleshooting" v-if="method.troubleshooting && method.troubleshooting.length > 0">
            <h4 class="section-title">
              <span class="section-icon">🔧</span>
              Troubleshooting
            </h4>
            <div class="troubleshooting-items">
              <div
                v-for="(issue, issueIndex) in method.troubleshooting"
                :key="issueIndex"
                class="troubleshooting-item"
              >
                <h5 class="issue-title">{{ issue.problem }}</h5>
                <p class="issue-solution">{{ issue.solution }}</p>
                <div class="issue-code" v-if="issue.code">
                  <div class="code-header">
                    <span class="code-language">{{ issue.language || 'bash' }}</span>
                    <button class="copy-btn" @click="copyCode(issue.code)" title="Copy code">
                      <span class="copy-icon">📋</span>
                      Copy
                    </button>
                  </div>
                  <pre class="code-block"><code>{{ issue.code }}</code></pre>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps } from 'vue'

const props = defineProps({
  title: {
    type: String,
    default: 'Installation Guide'
  },
  description: {
    type: String,
    default: ''
  },
  methods: {
    type: Array,
    required: true,
    validator: (methods) => {
      return methods.every(method => 
        method.hasOwnProperty('name') && 
        method.hasOwnProperty('type') && 
        method.hasOwnProperty('steps') &&
        Array.isArray(method.steps)
      )
    }
  },
  defaultMethod: {
    type: Number,
    default: 0
  }
})

const activeMethod = ref(props.defaultMethod)

const setActiveMethod = (index) => {
  activeMethod.value = index
}

const getMethodIcon = (type) => {
  const icons = {
    npm: '📦',
    yarn: '🧶',
    pnpm: '📦',
    composer: '🎼',
    pip: '🐍',
    gem: '💎',
    cargo: '📦',
    go: '🐹',
    docker: '🐳',
    manual: '⚙️',
    git: '🔀',
    download: '⬇️',
    cdn: '🌐'
  }
  return icons[type.toLowerCase()] || '📦'
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
  })
}
</script>

<style scoped>
.installation-guide {
  border: 1px solid var(--vp-c-border);
  border-radius: 12px;
  overflow: hidden;
  margin: 24px 0;
  background: var(--vp-c-bg-soft);
}

.guide-header {
  padding: 24px;
  background: var(--vp-c-bg-mute);
  border-bottom: 1px solid var(--vp-c-border);
}

.guide-title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 20px;
  font-weight: 600;
  color: var(--vp-c-text-1);
  margin: 0 0 8px 0;
}

.title-icon {
  font-size: 24px;
}

.guide-description {
  color: var(--vp-c-text-2);
  font-size: 14px;
  line-height: 1.6;
  margin: 0;
}

.installation-methods {
  background: var(--vp-c-bg-alt);
}

.method-tabs {
  display: flex;
  background: var(--vp-c-bg-mute);
  border-bottom: 1px solid var(--vp-c-border);
  overflow-x: auto;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

.method-tabs::-webkit-scrollbar {
  display: none;
}

.method-tab {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 16px 20px;
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

.method-tab:hover {
  color: var(--vp-c-text-1);
  background: var(--vp-c-bg-soft);
}

.method-tab.active {
  color: var(--vp-c-brand-1);
  background: var(--vp-c-bg-soft);
  border-bottom-color: var(--vp-c-brand-1);
}

.method-icon {
  font-size: 16px;
}

.method-panel {
  padding: 24px;
}

.method-info {
  margin-bottom: 24px;
}

.method-description {
  color: var(--vp-c-text-2);
  font-size: 14px;
  line-height: 1.6;
  margin: 0;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 16px;
  font-weight: 600;
  color: var(--vp-c-text-1);
  margin: 0 0 16px 0;
}

.section-icon {
  font-size: 16px;
}

.prerequisites {
  margin-bottom: 24px;
  padding: 16px;
  background: var(--vp-c-bg-mute);
  border-radius: 8px;
  border-left: 4px solid var(--vp-c-warning-1);
}

.prerequisites-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.prerequisite-item {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  color: var(--vp-c-text-2);
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 8px;
}

.prerequisite-item:last-child {
  margin-bottom: 0;
}

.prereq-icon {
  color: var(--vp-c-warning-1);
  font-weight: bold;
  margin-top: 2px;
}

.installation-steps {
  margin-bottom: 24px;
}

.steps-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.step-item {
  display: flex;
  gap: 16px;
  align-items: flex-start;
}

.step-number {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--vp-c-brand-1);
  color: white;
  font-size: 14px;
  font-weight: 600;
  flex-shrink: 0;
}

.step-content {
  flex: 1;
  min-width: 0;
}

.step-title {
  font-size: 16px;
  font-weight: 600;
  color: var(--vp-c-text-1);
  margin: 0 0 8px 0;
}

.step-description {
  color: var(--vp-c-text-2);
  font-size: 14px;
  line-height: 1.6;
  margin: 0 0 12px 0;
}

.step-code,
.verification-code,
.issue-code {
  margin: 12px 0;
  border: 1px solid var(--vp-c-border);
  border-radius: 8px;
  overflow: hidden;
}

.code-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  background: var(--vp-c-bg-mute);
  border-bottom: 1px solid var(--vp-c-border);
}

.code-language {
  font-size: 12px;
  font-weight: 600;
  color: var(--vp-c-text-2);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: 'JetBrains Mono', monospace;
}

.copy-btn {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 4px 8px;
  border: 1px solid var(--vp-c-border);
  border-radius: 4px;
  background: var(--vp-c-bg-soft);
  color: var(--vp-c-text-2);
  font-size: 11px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.copy-btn:hover {
  background: var(--vp-c-brand-1);
  color: white;
  border-color: var(--vp-c-brand-1);
}

.copy-icon {
  font-size: 10px;
}

.code-block {
  margin: 0;
  padding: 16px;
  background: var(--vp-c-bg-alt);
  color: var(--vp-c-text-1);
  font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace;
  font-size: 13px;
  line-height: 1.6;
  overflow-x: auto;
}

.code-block code {
  background: none;
  padding: 0;
  font-size: inherit;
  color: inherit;
}

.step-note {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  padding: 12px;
  background: var(--vp-c-bg-mute);
  border-radius: 6px;
  border-left: 3px solid var(--vp-c-tip-1);
  margin-top: 12px;
}

.note-icon {
  font-size: 14px;
  margin-top: 1px;
}

.note-text {
  color: var(--vp-c-text-2);
  font-size: 13px;
  line-height: 1.5;
}

.verification {
  margin-bottom: 24px;
  padding: 16px;
  background: var(--vp-c-bg-mute);
  border-radius: 8px;
  border-left: 4px solid var(--vp-c-tip-1);
}

.verification-description {
  color: var(--vp-c-text-2);
  font-size: 14px;
  line-height: 1.6;
  margin: 0 0 12px 0;
}

.troubleshooting {
  padding: 16px;
  background: var(--vp-c-bg-mute);
  border-radius: 8px;
  border-left: 4px solid var(--vp-c-danger-1);
}

.troubleshooting-items {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.troubleshooting-item {
  padding: 12px;
  background: var(--vp-c-bg-soft);
  border-radius: 6px;
}

.issue-title {
  font-size: 14px;
  font-weight: 600;
  color: var(--vp-c-text-1);
  margin: 0 0 8px 0;
}

.issue-solution {
  color: var(--vp-c-text-2);
  font-size: 13px;
  line-height: 1.6;
  margin: 0 0 8px 0;
}

/* Responsive design */
@media (max-width: 768px) {
  .guide-header {
    padding: 20px;
  }
  
  .method-panel {
    padding: 20px;
  }
  
  .method-tab {
    padding: 12px 16px;
    font-size: 13px;
  }
  
  .step-item {
    gap: 12px;
  }
  
  .step-number {
    width: 28px;
    height: 28px;
    font-size: 12px;
  }
  
  .code-block {
    padding: 12px;
    font-size: 12px;
  }
}

/* Focus styles for accessibility */
.method-tab:focus-visible,
.copy-btn:focus-visible {
  outline: 2px solid var(--vp-c-brand-1);
  outline-offset: 2px;
}

/* Animation */
.method-panel {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Disable animations for reduced motion */
@media (prefers-reduced-motion: reduce) {
  .method-panel {
    animation: none;
  }
}
</style>