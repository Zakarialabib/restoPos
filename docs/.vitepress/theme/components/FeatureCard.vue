<template>
  <div :class="['feature-card', size, { clickable: link }]" @click="handleClick">
    <div class="feature-icon" v-if="icon">
      <component :is="iconComponent" v-if="iconComponent" />
      <span v-else class="icon-emoji">{{ icon }}</span>
    </div>
    
    <div class="feature-content">
      <h3 class="feature-title">{{ title }}</h3>
      <p class="feature-description">{{ description }}</p>
      
      <div class="feature-details" v-if="details && details.length > 0">
        <ul class="details-list">
          <li v-for="detail in details" :key="detail" class="detail-item">
            <span class="detail-icon">✓</span>
            {{ detail }}
          </li>
        </ul>
      </div>
      
      <div class="feature-footer" v-if="link || badge">
        <StatusBadge v-if="badge" :status="badge" />
        <a v-if="link" :href="link" class="feature-link" @click.stop>
          {{ linkText || 'Learn more' }}
          <span class="link-arrow">→</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue'
import StatusBadge from './StatusBadge.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  icon: {
    type: String,
    default: ''
  },
  iconComponent: {
    type: [String, Object],
    default: null
  },
  details: {
    type: Array,
    default: () => []
  },
  link: {
    type: String,
    default: ''
  },
  linkText: {
    type: String,
    default: 'Learn more'
  },
  badge: {
    type: String,
    default: ''
  },
  size: {
    type: String,
    default: 'medium',
    validator: (value) => ['small', 'medium', 'large'].includes(value)
  }
})

const handleClick = () => {
  if (props.link) {
    window.open(props.link, '_blank')
  }
}
</script>

<style scoped>
.feature-card {
  display: flex;
  flex-direction: column;
  padding: 24px;
  border: 1px solid var(--vp-c-border);
  border-radius: 12px;
  background: var(--vp-c-bg-soft);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.feature-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--vp-c-brand-1), var(--vp-c-brand-2));
  opacity: 0;
  transition: opacity 0.3s ease;
}

.feature-card:hover {
  border-color: var(--vp-c-brand-1);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.feature-card:hover::before {
  opacity: 1;
}

.feature-card.clickable {
  cursor: pointer;
}

.feature-card.clickable:hover {
  background: var(--vp-c-bg-alt);
}

/* Size variations */
.feature-card.small {
  padding: 16px;
}

.feature-card.small .feature-title {
  font-size: 16px;
  margin-bottom: 8px;
}

.feature-card.small .feature-description {
  font-size: 13px;
}

.feature-card.large {
  padding: 32px;
}

.feature-card.large .feature-title {
  font-size: 24px;
  margin-bottom: 16px;
}

.feature-card.large .feature-description {
  font-size: 16px;
}

.feature-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 48px;
  height: 48px;
  margin-bottom: 16px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--vp-c-brand-1), var(--vp-c-brand-2));
  color: white;
  font-size: 24px;
  flex-shrink: 0;
}

.feature-card.small .feature-icon {
  width: 40px;
  height: 40px;
  font-size: 20px;
  margin-bottom: 12px;
}

.feature-card.large .feature-icon {
  width: 56px;
  height: 56px;
  font-size: 28px;
  margin-bottom: 20px;
}

.icon-emoji {
  font-style: normal;
}

.feature-content {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.feature-title {
  font-size: 18px;
  font-weight: 600;
  color: var(--vp-c-text-1);
  margin: 0 0 12px 0;
  line-height: 1.4;
}

.feature-description {
  font-size: 14px;
  color: var(--vp-c-text-2);
  line-height: 1.6;
  margin: 0 0 16px 0;
  flex: 1;
}

.feature-details {
  margin-bottom: 16px;
}

.details-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.detail-item {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  font-size: 13px;
  color: var(--vp-c-text-2);
  margin-bottom: 6px;
  line-height: 1.5;
}

.detail-item:last-child {
  margin-bottom: 0;
}

.detail-icon {
  color: var(--vp-c-brand-1);
  font-weight: 600;
  font-size: 12px;
  margin-top: 1px;
  flex-shrink: 0;
}

.feature-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-top: auto;
  padding-top: 16px;
}

.feature-link {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  color: var(--vp-c-brand-1);
  text-decoration: none;
  font-size: 13px;
  font-weight: 500;
  transition: all 0.2s ease;
  padding: 6px 12px;
  border-radius: 6px;
  border: 1px solid transparent;
}

.feature-link:hover {
  background: var(--vp-c-brand-1);
  color: white;
  border-color: var(--vp-c-brand-1);
  text-decoration: none;
}

.link-arrow {
  font-size: 12px;
  transition: transform 0.2s ease;
}

.feature-link:hover .link-arrow {
  transform: translateX(2px);
}

/* Dark mode adjustments */
.dark .feature-card {
  background: var(--vp-c-bg-mute);
  border-color: var(--vp-c-border-dark);
}

.dark .feature-card:hover {
  background: var(--vp-c-bg-soft);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
}

/* Grid layout support */
.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
  margin: 24px 0;
}

.features-grid .feature-card.small {
  min-height: 200px;
}

.features-grid .feature-card.medium {
  min-height: 250px;
}

.features-grid .feature-card.large {
  min-height: 300px;
}

/* Responsive design */
@media (max-width: 768px) {
  .features-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  
  .feature-card {
    padding: 20px;
  }
  
  .feature-card.large {
    padding: 24px;
  }
  
  .feature-title {
    font-size: 16px;
  }
  
  .feature-card.large .feature-title {
    font-size: 20px;
  }
  
  .feature-description {
    font-size: 13px;
  }
  
  .feature-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
}

/* Animation for card entrance */
.feature-card {
  animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Stagger animation for multiple cards */
.features-grid .feature-card:nth-child(1) { animation-delay: 0.1s; }
.features-grid .feature-card:nth-child(2) { animation-delay: 0.2s; }
.features-grid .feature-card:nth-child(3) { animation-delay: 0.3s; }
.features-grid .feature-card:nth-child(4) { animation-delay: 0.4s; }
.features-grid .feature-card:nth-child(5) { animation-delay: 0.5s; }
.features-grid .feature-card:nth-child(6) { animation-delay: 0.6s; }

/* Disable animations for reduced motion */
@media (prefers-reduced-motion: reduce) {
  .feature-card {
    animation: none;
  }
  
  .feature-card:hover {
    transform: none;
  }
  
  .feature-link:hover .link-arrow {
    transform: none;
  }
}

/* Focus styles for accessibility */
.feature-card.clickable:focus-visible {
  outline: 2px solid var(--vp-c-brand-1);
  outline-offset: 2px;
}

.feature-link:focus-visible {
  outline: 2px solid var(--vp-c-brand-1);
  outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
  .feature-card {
    border-width: 2px;
  }
  
  .feature-icon {
    border: 2px solid var(--vp-c-text-1);
  }
  
  .detail-icon {
    font-weight: 800;
  }
}
</style>