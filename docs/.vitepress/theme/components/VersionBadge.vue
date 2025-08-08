<template>
  <span :class="['version-badge', type, size]" :title="tooltip">
    <span class="version-icon" v-if="showIcon">{{ getVersionIcon(type) }}</span>
    <span class="version-text">
      <span class="version-label" v-if="showLabel">{{ getVersionLabel(type) }}</span>
      <span class="version-number">{{ version }}</span>
    </span>
    <span class="version-date" v-if="date && showDate">{{ formatDate(date) }}</span>
  </span>
</template>

<script setup>
import { defineProps, computed } from 'vue'

const props = defineProps({
  version: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'stable',
    validator: (value) => ['stable', 'beta', 'alpha', 'rc', 'dev', 'latest', 'lts', 'deprecated'].includes(value.toLowerCase())
  },
  size: {
    type: String,
    default: 'medium',
    validator: (value) => ['small', 'medium', 'large'].includes(value)
  },
  date: {
    type: [String, Date],
    default: null
  },
  showIcon: {
    type: Boolean,
    default: true
  },
  showLabel: {
    type: Boolean,
    default: false
  },
  showDate: {
    type: Boolean,
    default: false
  },
  tooltip: {
    type: String,
    default: ''
  }
})

const getVersionIcon = (type) => {
  const icons = {
    stable: '✅',
    beta: '🚧',
    alpha: '⚠️',
    rc: '🔄',
    dev: '🛠️',
    latest: '🆕',
    lts: '🔒',
    deprecated: '❌'
  }
  return icons[type.toLowerCase()] || '📦'
}

const getVersionLabel = (type) => {
  const labels = {
    stable: 'Stable',
    beta: 'Beta',
    alpha: 'Alpha',
    rc: 'RC',
    dev: 'Dev',
    latest: 'Latest',
    lts: 'LTS',
    deprecated: 'Deprecated'
  }
  return labels[type.toLowerCase()] || type
}

const formatDate = (date) => {
  if (!date) return ''
  
  const dateObj = typeof date === 'string' ? new Date(date) : date
  
  if (isNaN(dateObj.getTime())) return ''
  
  return dateObj.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>

<style scoped>
.version-badge {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  padding: 4px 8px;
  border-radius: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  white-space: nowrap;
  transition: all 0.2s ease;
  border: 1px solid transparent;
}

.version-badge:hover {
  transform: scale(1.05);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Size variations */
.version-badge.small {
  padding: 2px 6px;
  font-size: 10px;
  border-radius: 8px;
}

.version-badge.small .version-icon {
  font-size: 8px;
}

.version-badge.medium {
  padding: 4px 8px;
  font-size: 11px;
  border-radius: 10px;
}

.version-badge.medium .version-icon {
  font-size: 10px;
}

.version-badge.large {
  padding: 6px 12px;
  font-size: 12px;
  border-radius: 14px;
}

.version-badge.large .version-icon {
  font-size: 12px;
}

.version-icon {
  font-style: normal;
  flex-shrink: 0;
}

.version-text {
  display: flex;
  align-items: center;
  gap: 4px;
}

.version-label {
  font-weight: 700;
}

.version-number {
  font-weight: 600;
  font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace;
}

.version-date {
  font-size: 0.85em;
  opacity: 0.8;
  font-weight: 400;
  text-transform: none;
  letter-spacing: normal;
}

/* Type-based colors */
.version-badge.stable {
  background: #dcfce7;
  color: #166534;
  border-color: #bbf7d0;
}

.version-badge.beta {
  background: #fef3c7;
  color: #92400e;
  border-color: #fde68a;
}

.version-badge.alpha {
  background: #fee2e2;
  color: #991b1b;
  border-color: #fecaca;
}

.version-badge.rc {
  background: #dbeafe;
  color: #1e40af;
  border-color: #bfdbfe;
}

.version-badge.dev {
  background: #f3e8ff;
  color: #7c2d12;
  border-color: #e9d5ff;
}

.version-badge.latest {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-color: #667eea;
  animation: pulse 2s infinite;
}

.version-badge.lts {
  background: #f0fdf4;
  color: #15803d;
  border-color: #bbf7d0;
  position: relative;
}

.version-badge.lts::after {
  content: '';
  position: absolute;
  top: -2px;
  right: -2px;
  width: 6px;
  height: 6px;
  background: #15803d;
  border-radius: 50%;
  border: 1px solid white;
}

.version-badge.deprecated {
  background: #fef2f2;
  color: #dc2626;
  border-color: #fecaca;
  text-decoration: line-through;
  opacity: 0.7;
}

/* Dark mode adjustments */
.dark .version-badge.stable {
  background: #052e16;
  color: #bbf7d0;
  border-color: #166534;
}

.dark .version-badge.beta {
  background: #451a03;
  color: #fde68a;
  border-color: #92400e;
}

.dark .version-badge.alpha {
  background: #450a0a;
  color: #fecaca;
  border-color: #991b1b;
}

.dark .version-badge.rc {
  background: #1e3a8a;
  color: #bfdbfe;
  border-color: #1e40af;
}

.dark .version-badge.dev {
  background: #581c87;
  color: #e9d5ff;
  border-color: #7c2d12;
}

.dark .version-badge.latest {
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
  border-color: #4f46e5;
}

.dark .version-badge.lts {
  background: #052e16;
  color: #bbf7d0;
  border-color: #166534;
}

.dark .version-badge.lts::after {
  background: #bbf7d0;
  border-color: #052e16;
}

.dark .version-badge.deprecated {
  background: #450a0a;
  color: #fca5a5;
  border-color: #991b1b;
}

/* Special animations */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.9;
    transform: scale(1.02);
  }
}

.version-badge.latest {
  animation: pulse 3s infinite;
}

/* Hover effects for interactive badges */
.version-badge.stable:hover {
  background: #bbf7d0;
  border-color: #16a34a;
}

.version-badge.beta:hover {
  background: #fde68a;
  border-color: #d97706;
}

.version-badge.alpha:hover {
  background: #fecaca;
  border-color: #dc2626;
}

.version-badge.rc:hover {
  background: #bfdbfe;
  border-color: #2563eb;
}

.version-badge.dev:hover {
  background: #e9d5ff;
  border-color: #9333ea;
}

.version-badge.lts:hover {
  background: #dcfce7;
  border-color: #16a34a;
}

/* Dark mode hover effects */
.dark .version-badge.stable:hover {
  background: #166534;
  border-color: #22c55e;
}

.dark .version-badge.beta:hover {
  background: #92400e;
  border-color: #f59e0b;
}

.dark .version-badge.alpha:hover {
  background: #991b1b;
  border-color: #ef4444;
}

.dark .version-badge.rc:hover {
  background: #1e40af;
  border-color: #3b82f6;
}

.dark .version-badge.dev:hover {
  background: #7c2d12;
  border-color: #a855f7;
}

.dark .version-badge.lts:hover {
  background: #166534;
  border-color: #22c55e;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .version-badge {
    font-size: 10px;
    padding: 3px 6px;
  }
  
  .version-badge.large {
    font-size: 11px;
    padding: 4px 8px;
  }
  
  .version-date {
    display: none;
  }
}

/* Accessibility improvements */
.version-badge:focus-visible {
  outline: 2px solid var(--vp-c-brand-1);
  outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .version-badge {
    border-width: 2px;
    font-weight: 700;
  }
  
  .version-badge.latest {
    background: #000;
    color: #fff;
    border-color: #fff;
  }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
  .version-badge {
    animation: none;
  }
  
  .version-badge:hover {
    transform: none;
  }
  
  .version-badge.latest {
    animation: none;
  }
}

/* Print styles */
@media print {
  .version-badge {
    background: transparent !important;
    color: #000 !important;
    border: 1px solid #000 !important;
    box-shadow: none !important;
  }
}

/* Utility classes for common combinations */
.version-badge.compact {
  padding: 2px 4px;
  font-size: 9px;
  gap: 2px;
}

.version-badge.compact .version-icon {
  font-size: 8px;
}

.version-badge.minimal {
  background: transparent;
  border: 1px solid currentColor;
  color: var(--vp-c-text-2);
}

.version-badge.minimal:hover {
  background: var(--vp-c-bg-mute);
  color: var(--vp-c-text-1);
}
</style>