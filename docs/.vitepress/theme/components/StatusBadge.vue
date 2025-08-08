<template>
  <span :class="['status-badge', status.toLowerCase()]">
    <span class="status-icon">{{ getStatusIcon(status) }}</span>
    {{ getStatusText(status) }}
  </span>
</template>

<script setup>
import { defineProps, computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    default: 'stable',
    validator: (value) => ['stable', 'beta', 'deprecated', 'new', 'experimental', 'legacy'].includes(value.toLowerCase())
  }
})

const getStatusIcon = (status) => {
  const icons = {
    stable: '✅',
    beta: '🚧',
    deprecated: '⚠️',
    new: '✨',
    experimental: '🧪',
    legacy: '📦'
  }
  return icons[status.toLowerCase()] || '📋'
}

const getStatusText = (status) => {
  const texts = {
    stable: 'Stable',
    beta: 'Beta',
    deprecated: 'Deprecated',
    new: 'New',
    experimental: 'Experimental',
    legacy: 'Legacy'
  }
  return texts[status.toLowerCase()] || status
}
</script>

<style scoped>
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  white-space: nowrap;
  transition: all 0.2s ease;
}

.status-badge:hover {
  transform: scale(1.05);
}

.status-icon {
  font-size: 10px;
}

/* Status colors */
.status-badge.stable {
  background: #dcfce7;
  color: #166534;
  border: 1px solid #bbf7d0;
}

.status-badge.beta {
  background: #fef3c7;
  color: #92400e;
  border: 1px solid #fde68a;
}

.status-badge.deprecated {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fecaca;
}

.status-badge.new {
  background: #dbeafe;
  color: #1e40af;
  border: 1px solid #bfdbfe;
}

.status-badge.experimental {
  background: #f3e8ff;
  color: #7c2d12;
  border: 1px solid #e9d5ff;
}

.status-badge.legacy {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #e2e8f0;
}

/* Dark mode adjustments */
.dark .status-badge.stable {
  background: #052e16;
  color: #bbf7d0;
  border-color: #166534;
}

.dark .status-badge.beta {
  background: #451a03;
  color: #fde68a;
  border-color: #92400e;
}

.dark .status-badge.deprecated {
  background: #450a0a;
  color: #fecaca;
  border-color: #991b1b;
}

.dark .status-badge.new {
  background: #1e3a8a;
  color: #bfdbfe;
  border-color: #1e40af;
}

.dark .status-badge.experimental {
  background: #581c87;
  color: #e9d5ff;
  border-color: #7c2d12;
}

.dark .status-badge.legacy {
  background: #0f172a;
  color: #e2e8f0;
  border-color: #475569;
}

/* Animation for new status */
.status-badge.new {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
  .status-badge {
    animation: none;
  }
  
  .status-badge:hover {
    transform: none;
  }
}
</style>