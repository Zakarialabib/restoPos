import { h } from 'vue'
import DefaultTheme from 'vitepress/theme'
import './style.css'
import './custom.css'

// Custom components
import ApiEndpoint from './components/ApiEndpoint.vue'
import CodeTabs from './components/CodeTabs.vue'
import FeatureCard from   './components/FeatureCard.vue'
import InstallationGuide from './components/InstallationGuide.vue'
import StatusBadge from './components/StatusBadge.vue'
import VersionBadge from './components/VersionBadge.vue'

export default {
  extends: DefaultTheme,
  Layout: () => {
    return h(DefaultTheme.Layout, null, {
      // Custom layout slots
      'home-hero-before': () => h('div', { class: 'hero-gradient' }),
      'home-features-after': () => h('div', { class: 'features-gradient' }),
      'doc-footer-before': () => h('div', { class: 'doc-footer-gradient' }),
      'nav-bar-content-after': () => h('div', { class: 'nav-gradient' })
    })
  },
  enhanceApp({ app, router, siteData }) {
    // Register global components
    app.component('ApiEndpoint', ApiEndpoint)
    app.component('CodeTabs', CodeTabs)
    app.component('FeatureCard', FeatureCard)
    app.component('InstallationGuide', InstallationGuide)
    app.component('StatusBadge', StatusBadge)
    app.component('VersionBadge', VersionBadge)
    
    // Global properties
    app.config.globalProperties.$restopos = {
      version: '2.0.0',
      apiVersion: 'v1',
      baseUrl: 'https://api.restopos.com'
    }
    
    // Router hooks for analytics and custom behavior
    router.onAfterRouteChanged = (to) => {
      // Custom analytics or tracking
      if (typeof gtag !== 'undefined') {
        gtag('config', 'GA_MEASUREMENT_ID', {
          page_path: to
        })
      }
    }
    
    // Add custom directives
    app.directive('highlight', {
      mounted(el, binding) {
        if (binding.value) {
          el.style.backgroundColor = '#fef3c7'
          el.style.padding = '2px 4px'
          el.style.borderRadius = '4px'
        }
      }
    })
    
    // Add global methods
    app.config.globalProperties.$copyToClipboard = (text) => {
      navigator.clipboard.writeText(text).then(() => {
        // Show toast notification
        const toast = document.createElement('div')
        toast.className = 'copy-toast'
        toast.textContent = 'Copied to clipboard!'
        document.body.appendChild(toast)
        
        setTimeout(() => {
          document.body.removeChild(toast)
        }, 2000)
      })
    }
  }
}
