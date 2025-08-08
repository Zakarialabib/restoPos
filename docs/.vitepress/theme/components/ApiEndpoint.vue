<template>
  <div class="api-endpoint">
    <div class="api-header">
      <span :class="['api-method', method.toLowerCase()]">
        {{ method.toUpperCase() }}
      </span>
      <code class="api-url">{{ url }}</code>
      <StatusBadge v-if="status" :status="status" />
    </div>
    
    <div v-if="description" class="api-description">
      <p>{{ description }}</p>
    </div>
    
    <div v-if="parameters && parameters.length" class="api-section">
      <h4>Parameters</h4>
      <div class="parameters-table">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Required</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="param in parameters" :key="param.name">
              <td><code>{{ param.name }}</code></td>
              <td><span class="param-type">{{ param.type }}</span></td>
              <td>
                <span :class="['param-required', param.required ? 'required' : 'optional']">
                  {{ param.required ? 'Required' : 'Optional' }}
                </span>
              </td>
              <td>{{ param.description }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    
    <div v-if="example" class="api-section">
      <h4>Example Request</h4>
      <div class="code-example">
        <pre><code>{{ example }}</code></pre>
        <button 
          class="copy-btn" 
          @click="copyToClipboard(example)"
          title="Copy to clipboard"
        >
          📋
        </button>
      </div>
    </div>
    
    <div v-if="response" class="api-section">
      <h4>Example Response</h4>
      <div class="code-example">
        <pre><code>{{ response }}</code></pre>
        <button 
          class="copy-btn" 
          @click="copyToClipboard(response)"
          title="Copy to clipboard"
        >
          📋
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import StatusBadge from './StatusBadge.vue'

defineProps({
  method: {
    type: String,
    required: true,
    validator: (value) => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'].includes(value.toUpperCase())
  },
  url: {
    type: String,
    required: true
  },
  description: {
    type: String,
    default: ''
  },
  status: {
    type: String,
    default: 'stable'
  },
  parameters: {
    type: Array,
    default: () => []
  },
  example: {
    type: String,
    default: ''
  },
  response: {
    type: String,
    default: ''
  }
})

const copyToClipboard = (text) => {
  navigator.clipboard.writeText(text).then(() => {
    // Show toast notification
    const toast = document.createElement('div')
    toast.className = 'copy-toast'
    toast.textContent = 'Copied to clipboard!'
    document.body.appendChild(toast)
    
    setTimeout(() => {
      if (document.body.contains(toast)) {
        document.body.removeChild(toast)
      }
    }, 2000)
  }).catch(err => {
    console.error('Failed to copy text: ', err)
  })
}
</script>

<style scoped>
.api-endpoint {
  background: var(--vp-c-bg-soft);
  border: 1px solid var(--vp-c-border);
  border-radius: 12px;
  padding: 20px;
  margin: 20px 0;
  transition: all 0.3s ease;
}

.api-endpoint:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border-color: var(--vp-c-brand-1);
}

.api-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
  flex-wrap: wrap;
}

.api-method {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: 'JetBrains Mono', monospace;
}

.api-method.get { background: #10b981; color: white; }
.api-method.post { background: #3b82f6; color: white; }
.api-method.put { background: #f59e0b; color: white; }
.api-method.delete { background: #ef4444; color: white; }
.api-method.patch { background: #8b5cf6; color: white; }

.api-url {
  font-family: 'JetBrains Mono', monospace;
  font-size: 14px;
  font-weight: 500;
  color: var(--vp-c-text-1);
  background: var(--vp-c-bg-mute);
  padding: 4px 8px;
  border-radius: 4px;
  flex: 1;
  min-width: 200px;
}

.api-description {
  margin-bottom: 16px;
  color: var(--vp-c-text-2);
  line-height: 1.6;
}

.api-section {
  margin-top: 20px;
}

.api-section h4 {
  margin: 0 0 12px 0;
  color: var(--vp-c-brand-1);
  font-size: 16px;
  font-weight: 600;
}

.parameters-table {
  overflow-x: auto;
}

.parameters-table table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 8px;
  overflow: hidden;
  border: 1px solid var(--vp-c-border);
}

.parameters-table th,
.parameters-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid var(--vp-c-border);
}

.parameters-table th {
  background: var(--vp-c-bg-mute);
  font-weight: 600;
  color: var(--vp-c-text-1);
  font-size: 14px;
}

.parameters-table td {
  font-size: 14px;
  color: var(--vp-c-text-2);
}

.parameters-table tr:last-child td {
  border-bottom: none;
}

.param-type {
  background: var(--vp-c-bg-mute);
  padding: 2px 6px;
  border-radius: 4px;
  font-family: 'JetBrains Mono', monospace;
  font-size: 12px;
  color: var(--vp-c-brand-1);
}

.param-required {
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
}

.param-required.required {
  background: #fee2e2;
  color: #991b1b;
}

.param-required.optional {
  background: #f0f9ff;
  color: #0369a1;
}

.code-example {
  position: relative;
  background: var(--vp-c-bg-alt);
  border: 1px solid var(--vp-c-border);
  border-radius: 8px;
  overflow: hidden;
}

.code-example pre {
  margin: 0;
  padding: 16px;
  overflow-x: auto;
  font-family: 'JetBrains Mono', monospace;
  font-size: 13px;
  line-height: 1.5;
}

.code-example code {
  background: none;
  padding: 0;
  font-size: inherit;
  color: var(--vp-c-text-1);
}

.copy-btn {
  position: absolute;
  top: 8px;
  right: 8px;
  background: var(--vp-c-bg-soft);
  border: 1px solid var(--vp-c-border);
  border-radius: 4px;
  padding: 4px 8px;
  cursor: pointer;
  font-size: 12px;
  transition: all 0.2s ease;
  opacity: 0.7;
}

.copy-btn:hover {
  opacity: 1;
  background: var(--vp-c-brand-1);
  color: white;
  border-color: var(--vp-c-brand-1);
}

/* Dark mode adjustments */
.dark .api-method.get { background: #059669; }
.dark .api-method.post { background: #2563eb; }
.dark .api-method.put { background: #d97706; }
.dark .api-method.delete { background: #dc2626; }
.dark .api-method.patch { background: #7c3aed; }

.dark .param-required.required {
  background: #450a0a;
  color: #fca5a5;
}

.dark .param-required.optional {
  background: #0c4a6e;
  color: #7dd3fc;
}

/* Responsive design */
@media (max-width: 768px) {
  .api-endpoint {
    padding: 16px;
  }
  
  .api-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
  
  .api-url {
    min-width: auto;
    width: 100%;
  }
  
  .parameters-table {
    font-size: 12px;
  }
  
  .parameters-table th,
  .parameters-table td {
    padding: 8px;
  }
}
</style>