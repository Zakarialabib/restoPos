import { defineConfig } from 'vitepress'
import { tabsMarkdownPlugin } from 'vitepress-plugin-tabs'
import { Feed } from 'feed'
import { resolve } from 'path'

// Import only essential and working markdown-it plugins
import markdownItContainer from 'markdown-it-container'
import markdownItAnchor from 'markdown-it-anchor'

const baseUrl = 'https://docs.restopos.com'
const title = 'RestoPos Documentation'
const description = 'Comprehensive documentation for RestoPos - Modern Restaurant Management System'

// Enhanced navigation structure for RestoPos
const nav = {
  en: [
    { text: 'Home', link: '/' },
    { text: 'Getting Started', link: '/getting-started/' },
    {
      text: 'System Overview',
      items: [
        { text: 'Architecture', link: '/overview/architecture' },
        { text: 'Features', link: '/overview/features' },
        { text: 'Routes & Endpoints', link: '/overview/routes' },
        { text: 'Installation', link: '/overview/installation' }
      ]
    },
    {
      text: 'Customer Portal',
      items: [
        { text: 'Menu Display', link: '/customer/menu' },
        { text: 'Ordering System', link: '/customer/ordering' },
        { text: 'Composable Products', link: '/customer/composable' },
        { text: 'Theme Customization', link: '/customer/themes' }
      ]
    },
    {
      text: 'TV Display System',
      items: [
        { text: 'TV Menu Setup', link: '/tv-display/setup' },
        { text: 'Theme Showcase', link: '/tv-display/themes' },
        { text: 'Content Management', link: '/tv-display/content' },
        { text: 'Display Controls', link: '/tv-display/controls' }
      ]
    },
    {
      text: 'Admin Management',
      items: [
        { text: 'Dashboard', link: '/admin/dashboard' },
        { text: 'POS System', link: '/admin/pos' },
        { text: 'Product Management', link: '/admin/products' },
        { text: 'Inventory Management', link: '/admin/inventory' },
        { text: 'Order Management', link: '/admin/orders' },
        { text: 'Kitchen Management', link: '/admin/kitchen' },
        { text: 'Finance & Reports', link: '/admin/finance' },
        { text: 'Customer Management', link: '/admin/customers' },
        { text: 'Supplier Management', link: '/admin/suppliers' },
        { text: 'Settings & Configuration', link: '/admin/settings' }
      ]
    },
    {
      text: 'Printing System',
      items: [
        { text: 'Menu Printing', link: '/printing/menu' },
        { text: 'Order Tickets', link: '/printing/orders' },
        { text: 'Kitchen Tickets', link: '/printing/kitchen' },
        { text: 'Reports & Analytics', link: '/printing/reports' },
        { text: 'Low Stock Alerts', link: '/printing/alerts' },
        { text: 'Expiry Notifications', link: '/printing/expiry' }
      ]
    },
    {
      text: 'API Reference',
      items: [
        { text: 'Overview', link: '/api/' },
        { text: 'Authentication', link: '/api/authentication' },
        { text: 'Orders API', link: '/api/orders' },
        { text: 'Menu API', link: '/api/menu' },
        { text: 'Inventory API', link: '/api/inventory' },
        { text: 'Kitchen API', link: '/api/kitchen' },
        { text: 'Printing API', link: '/api/printing' }
      ]
    },
    {
      text: 'Developer Guide',
      items: [
        { text: 'Setup & Installation', link: '/developer/setup' },
        { text: 'Architecture', link: '/developer/architecture' },
        { text: 'Livewire Components', link: '/developer/livewire' },
        { text: 'Theme System', link: '/developer/themes' },
        { text: 'Printing Integration', link: '/developer/printing' },
        { text: 'API Development', link: '/developer/api' },
        { text: 'Deployment', link: '/developer/deployment' }
      ]
    },
    {
      text: 'Resources',
      items: [
        { text: 'Changelog', link: '/resources/changelog' },
        { text: 'Migration Guide', link: '/resources/migration' },
        { text: 'FAQ', link: '/resources/faq' },
        { text: 'Troubleshooting', link: '/resources/troubleshooting' },
        { text: 'Community', link: '/resources/community' }
      ]
    }
  ],
  fr: [
    { text: 'Accueil', link: '/fr/' },
    { text: 'Commencer', link: '/fr/getting-started/' },
    {
      text: 'Vue d\'ensemble',
      items: [
        { text: 'Architecture', link: '/fr/overview/architecture' },
        { text: 'Fonctionnalités', link: '/fr/overview/features' },
        { text: 'Routes & Endpoints', link: '/fr/overview/routes' },
        { text: 'Installation', link: '/fr/overview/installation' }
      ]
    },
    {
      text: 'Portail Client',
      items: [
        { text: 'Affichage Menu', link: '/fr/customer/menu' },
        { text: 'Système de Commande', link: '/fr/customer/ordering' },
        { text: 'Produits Composites', link: '/fr/customer/composable' },
        { text: 'Personnalisation Thème', link: '/fr/customer/themes' }
      ]
    },
    {
      text: 'Système TV',
      items: [
        { text: 'Configuration TV', link: '/fr/tv-display/setup' },
        { text: 'Vitrine Thèmes', link: '/fr/tv-display/themes' },
        { text: 'Gestion Contenu', link: '/fr/tv-display/content' },
        { text: 'Contrôles Affichage', link: '/fr/tv-display/controls' }
      ]
    },
    {
      text: 'Gestion Admin',
      items: [
        { text: 'Tableau de bord', link: '/fr/admin/dashboard' },
        { text: 'Système POS', link: '/fr/admin/pos' },
        { text: 'Gestion Produits', link: '/fr/admin/products' },
        { text: 'Gestion Inventaire', link: '/fr/admin/inventory' },
        { text: 'Gestion Commandes', link: '/fr/admin/orders' },
        { text: 'Gestion Cuisine', link: '/fr/admin/kitchen' },
        { text: 'Finance & Rapports', link: '/fr/admin/finance' },
        { text: 'Gestion Clients', link: '/fr/admin/customers' },
        { text: 'Gestion Fournisseurs', link: '/fr/admin/suppliers' },
        { text: 'Paramètres', link: '/fr/admin/settings' }
      ]
    },
    {
      text: 'Système Impression',
      items: [
        { text: 'Impression Menu', link: '/fr/printing/menu' },
        { text: 'Tickets Commande', link: '/fr/printing/orders' },
        { text: 'Tickets Cuisine', link: '/fr/printing/kitchen' },
        { text: 'Rapports & Analytics', link: '/fr/printing/reports' },
        { text: 'Alertes Stock', link: '/fr/printing/alerts' },
        { text: 'Notifications Expiration', link: '/fr/printing/expiry' }
      ]
    }
  ],
  ar: [
    { text: 'الرئيسية', link: '/ar/' },
    { text: 'البدء', link: '/ar/getting-started/' },
    {
      text: 'نظرة عامة على النظام',
      items: [
        { text: 'الهندسة المعمارية', link: '/ar/overview/architecture' },
        { text: 'الميزات', link: '/ar/overview/features' },
        { text: 'المسارات والنقاط النهائية', link: '/ar/overview/routes' },
        { text: 'التثبيت', link: '/ar/overview/installation' }
      ]
    },
    {
      text: 'بوابة العملاء',
      items: [
        { text: 'عرض القائمة', link: '/ar/customer/menu' },
        { text: 'نظام الطلبات', link: '/ar/customer/ordering' },
        { text: 'المنتجات المركبة', link: '/ar/customer/composable' },
        { text: 'تخصيص المظهر', link: '/ar/customer/themes' }
      ]
    },
    {
      text: 'نظام العرض التلفزيوني',
      items: [
        { text: 'إعداد التلفاز', link: '/ar/tv-display/setup' },
        { text: 'عرض المظاهر', link: '/ar/tv-display/themes' },
        { text: 'إدارة المحتوى', link: '/ar/tv-display/content' },
        { text: 'أدوات التحكم', link: '/ar/tv-display/controls' }
      ]
    },
    {
      text: 'إدارة النظام',
      items: [
        { text: 'لوحة التحكم', link: '/ar/admin/dashboard' },
        { text: 'نظام نقاط البيع', link: '/ar/admin/pos' },
        { text: 'إدارة المنتجات', link: '/ar/admin/products' },
        { text: 'إدارة المخزون', link: '/ar/admin/inventory' },
        { text: 'إدارة الطلبات', link: '/ar/admin/orders' },
        { text: 'إدارة المطبخ', link: '/ar/admin/kitchen' },
        { text: 'المالية والتقارير', link: '/ar/admin/finance' },
        { text: 'إدارة العملاء', link: '/ar/admin/customers' },
        { text: 'إدارة الموردين', link: '/ar/admin/suppliers' },
        { text: 'الإعدادات', link: '/ar/admin/settings' }
      ]
    },
    {
      text: 'نظام الطباعة',
      items: [
        { text: 'طباعة القائمة', link: '/ar/printing/menu' },
        { text: 'تذاكر الطلبات', link: '/ar/printing/orders' },
        { text: 'تذاكر المطبخ', link: '/ar/printing/kitchen' },
        { text: 'التقارير والتحليلات', link: '/ar/printing/reports' },
        { text: 'تنبيهات المخزون', link: '/ar/printing/alerts' },
        { text: 'إشعارات انتهاء الصلاحية', link: '/ar/printing/expiry' }
      ]
    }
  ]
}

// Enhanced sidebar structure for RestoPos
const sidebar = {
  en: {
    '/getting-started/': [
      {
        text: 'Getting Started',
        items: [
          { text: 'Introduction', link: '/getting-started/' },
          { text: 'Installation', link: '/getting-started/installation' },
          { text: 'Quick Setup', link: '/getting-started/quick-setup' },
          { text: 'First Steps', link: '/getting-started/first-steps' },
          { text: 'Configuration', link: '/getting-started/configuration' }
        ]
      }
    ],
    '/overview/': [
      {
        text: 'System Overview',
        items: [
          { text: 'Architecture', link: '/overview/architecture' },
          { text: 'Features', link: '/overview/features' },
          { text: 'Routes & Endpoints', link: '/overview/routes' },
          { text: 'Installation Guide', link: '/overview/installation' }
        ]
      }
    ],
    '/customer/': [
      {
        text: 'Customer Portal',
        items: [
          { text: 'Menu Display', link: '/customer/menu' },
          { text: 'Ordering System', link: '/customer/ordering' },
          { text: 'Composable Products', link: '/customer/composable' },
          { text: 'Theme Customization', link: '/customer/themes' }
        ]
      }
    ],
    '/tv-display/': [
      {
        text: 'TV Display System',
        items: [
          { text: 'TV Menu Setup', link: '/tv-display/setup' },
          { text: 'Theme Showcase', link: '/tv-display/themes' },
          { text: 'Content Management', link: '/tv-display/content' },
          { text: 'Display Controls', link: '/tv-display/controls' }
        ]
      }
    ],
    '/admin/': [
      {
        text: 'Admin Management',
        items: [
          { text: 'Dashboard', link: '/admin/dashboard' },
          { text: 'POS System', link: '/admin/pos' },
          { text: 'Product Management', link: '/admin/products' },
          { text: 'Inventory Management', link: '/admin/inventory' },
          { text: 'Order Management', link: '/admin/orders' },
          { text: 'Kitchen Management', link: '/admin/kitchen' },
          { text: 'Finance & Reports', link: '/admin/finance' },
          { text: 'Customer Management', link: '/admin/customers' },
          { text: 'Supplier Management', link: '/admin/suppliers' },
          { text: 'Settings & Configuration', link: '/admin/settings' }
        ]
      }
    ],
    '/printing/': [
      {
        text: 'Printing System',
        items: [
          { text: 'Menu Printing', link: '/printing/menu' },
          { text: 'Order Tickets', link: '/printing/orders' },
          { text: 'Kitchen Tickets', link: '/printing/kitchen' },
          { text: 'Reports & Analytics', link: '/printing/reports' },
          { text: 'Low Stock Alerts', link: '/printing/alerts' },
          { text: 'Expiry Notifications', link: '/printing/expiry' }
        ]
      }
    ],
    '/api/': [
      {
        text: 'API Reference',
        items: [
          { text: 'Overview', link: '/api/' },
          { text: 'Authentication', link: '/api/authentication' },
          { text: 'Orders API', link: '/api/orders' },
          { text: 'Menu API', link: '/api/menu' },
          { text: 'Inventory API', link: '/api/inventory' },
          { text: 'Kitchen API', link: '/api/kitchen' },
          { text: 'Printing API', link: '/api/printing' }
        ]
      }
    ],
    '/developer/': [
      {
        text: 'Developer Guide',
        items: [
          { text: 'Setup & Installation', link: '/developer/setup' },
          { text: 'Architecture', link: '/developer/architecture' },
          { text: 'Livewire Components', link: '/developer/livewire' },
          { text: 'Theme System', link: '/developer/themes' },
          { text: 'Printing Integration', link: '/developer/printing' },
          { text: 'API Development', link: '/developer/api' },
          { text: 'Deployment', link: '/developer/deployment' }
        ]
      }
    ]
  },
  fr: {
    '/fr/getting-started/': [
      {
        text: 'Commencer',
        items: [
          { text: 'Introduction', link: '/fr/getting-started/' },
          { text: 'Installation', link: '/fr/getting-started/installation' },
          { text: 'Configuration rapide', link: '/fr/getting-started/quick-setup' },
          { text: 'Premiers pas', link: '/fr/getting-started/first-steps' },
          { text: 'Configuration', link: '/fr/getting-started/configuration' }
        ]
      }
    ],
    '/fr/overview/': [
      {
        text: 'Vue d\'ensemble',
        items: [
          { text: 'Architecture', link: '/fr/overview/architecture' },
          { text: 'Fonctionnalités', link: '/fr/overview/features' },
          { text: 'Routes & Endpoints', link: '/fr/overview/routes' },
          { text: 'Guide d\'installation', link: '/fr/overview/installation' }
        ]
      }
    ]
  },
  ar: {
    '/ar/getting-started/': [
      {
        text: 'البدء',
        items: [
          { text: 'مقدمة', link: '/ar/getting-started/' },
          { text: 'التثبيت', link: '/ar/getting-started/installation' },
          { text: 'الإعداد السريع', link: '/ar/getting-started/quick-setup' },
          { text: 'الخطوات الأولى', link: '/ar/getting-started/first-steps' },
          { text: 'التكوين', link: '/ar/getting-started/configuration' }
        ]
      }
    ],
    '/ar/overview/': [
      {
        text: 'نظرة عامة على النظام',
        items: [
          { text: 'الهندسة المعمارية', link: '/ar/overview/architecture' },
          { text: 'الميزات', link: '/ar/overview/features' },
          { text: 'المسارات والنقاط النهائية', link: '/ar/overview/routes' },
          { text: 'دليل التثبيت', link: '/ar/overview/installation' }
        ]
      }
    ]
  }
}

export default defineConfig({
    title,
    description,
    base: '/',
    lang: 'en-US',
    
    // Enhanced head configuration
    head: [
      ['link', { rel: 'icon', href: '/favicon.ico' }],
      ['link', { rel: 'apple-touch-icon', sizes: '180x180', href: '/apple-touch-icon.png' }],
      ['link', { rel: 'icon', type: 'image/png', sizes: '32x32', href: '/favicon-32x32.png' }],
      ['link', { rel: 'icon', type: 'image/png', sizes: '16x16', href: '/favicon-16x16.png' }],
      ['link', { rel: 'manifest', href: '/site.webmanifest' }],
      ['meta', { name: 'theme-color', content: '#3c82f6' }],
      ['meta', { name: 'og:type', content: 'website' }],
      ['meta', { name: 'og:locale', content: 'en' }],
      ['meta', { name: 'og:title', content: title }],
      ['meta', { name: 'og:description', content: description }],
      ['meta', { name: 'og:site_name', content: 'RestoPos Documentation' }],
      ['meta', { name: 'og:url', content: baseUrl }],
      ['meta', { name: 'og:image', content: `${baseUrl}/og-image.png` }],
      ['meta', { name: 'twitter:card', content: 'summary_large_image' }],
      ['meta', { name: 'twitter:title', content: title }],
      ['meta', { name: 'twitter:description', content: description }],
      ['meta', { name: 'twitter:image', content: `${baseUrl}/og-image.png` }],
      ['script', { type: 'application/ld+json' }, JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebSite',
        name: title,
        description,
        url: baseUrl
      })]
    ],

    // Multi-language configuration
    locales: {
      root: {
        label: 'English',
        lang: 'en-US',
        title,
        description,
        themeConfig: {
          nav: nav.en,
          sidebar: sidebar.en,
          editLink: {
            pattern: 'https://github.com/restopos/restopos/edit/main/docs/:path',
            text: 'Edit this page on GitHub'
          },
          footer: {
            message: 'Released under the MIT License.',
            copyright: 'Copyright © 2024 RestoPos Team'
          },
          docFooter: {
            prev: 'Previous page',
            next: 'Next page'
          },
          outline: {
            label: 'On this page'
          },
          lastUpdated: {
            text: 'Last updated',
            formatOptions: {
              dateStyle: 'short',
              timeStyle: 'medium'
            }
          },
          langMenuLabel: 'Change language',
          returnToTopLabel: 'Return to top',
          sidebarMenuLabel: 'Menu',
          darkModeSwitchLabel: 'Appearance',
          lightModeSwitchTitle: 'Switch to light theme',
          darkModeSwitchTitle: 'Switch to dark theme'
        }
      },
      fr: {
        label: 'Français',
        lang: 'fr-FR',
        title: 'Documentation RestoPos',
        description: 'Documentation complète pour RestoPos - Système moderne de gestion de restaurant',
        themeConfig: {
          nav: nav.fr,
          sidebar: sidebar.fr,
          editLink: {
            pattern: 'https://github.com/restopos/restopos/edit/main/docs/:path',
            text: 'Modifier cette page sur GitHub'
          },
          footer: {
            message: 'Publié sous licence MIT.',
            copyright: 'Copyright © 2024 Équipe RestoPos'
          },
          docFooter: {
            prev: 'Page précédente',
            next: 'Page suivante'
          },
          outline: {
            label: 'Sur cette page'
          },
          lastUpdated: {
            text: 'Dernière mise à jour',
            formatOptions: {
              dateStyle: 'short',
              timeStyle: 'medium'
            }
          },
          langMenuLabel: 'Changer de langue',
          returnToTopLabel: 'Retour en haut',
          sidebarMenuLabel: 'Menu',
          darkModeSwitchLabel: 'Apparence',
          lightModeSwitchTitle: 'Basculer vers le thème clair',
          darkModeSwitchTitle: 'Basculer vers le thème sombre'
        }
      },
      ar: {
        label: 'العربية',
        lang: 'ar-SA',
        dir: 'rtl',
        title: 'وثائق RestoPos',
        description: 'وثائق شاملة لـ RestoPos - نظام إدارة المطاعم الحديث',
        themeConfig: {
          nav: nav.ar,
          sidebar: sidebar.ar,
          editLink: {
            pattern: 'https://github.com/restopos/restopos/edit/main/docs/:path',
            text: 'تحرير هذه الصفحة على GitHub'
          },
          footer: {
            message: 'صدر تحت رخصة MIT.',
            copyright: 'حقوق الطبع والنشر © 2024 فريق RestoPos'
          },
          docFooter: {
            prev: 'الصفحة السابقة',
            next: 'الصفحة التالية'
          },
          outline: {
            label: 'في هذه الصفحة'
          },
          lastUpdated: {
            text: 'آخر تحديث',
            formatOptions: {
              dateStyle: 'short',
              timeStyle: 'medium'
            }
          },
          langMenuLabel: 'تغيير اللغة',
          returnToTopLabel: 'العودة إلى الأعلى',
          sidebarMenuLabel: 'القائمة',
          darkModeSwitchLabel: 'المظهر',
          lightModeSwitchTitle: 'التبديل إلى المظهر الفاتح',
          darkModeSwitchTitle: 'التبديل إلى المظهر الداكن'
        }
      }
    },

    // Global theme configuration
    themeConfig: {
      logo: '/logo.svg',
      siteTitle: 'RestoPos Docs',
      
      // Enhanced search configuration
      search: {
        provider: 'local',
        options: {
          locales: {
            root: {
              translations: {
                button: {
                  buttonText: 'Search docs',
                  buttonAriaLabel: 'Search docs'
                },
                modal: {
                  noResultsText: 'No results for',
                  resetButtonTitle: 'Clear search',
                  footer: {
                    selectText: 'to select',
                    navigateText: 'to navigate',
                    closeText: 'to close'
                  }
                }
              }
            },
            fr: {
              translations: {
                button: {
                  buttonText: 'Rechercher dans la documentation',
                  buttonAriaLabel: 'Rechercher dans la documentation'
                },
                modal: {
                  noResultsText: 'Aucun résultat pour',
                  resetButtonTitle: 'Effacer la recherche',
                  footer: {
                    selectText: 'pour sélectionner',
                    navigateText: 'pour naviguer',
                    closeText: 'pour fermer'
                  }
                }
              }
            },
            ar: {
              translations: {
                button: {
                  buttonText: 'البحث في الوثائق',
                  buttonAriaLabel: 'البحث في الوثائق'
                },
                modal: {
                  noResultsText: 'لا توجد نتائج لـ',
                  resetButtonTitle: 'مسح البحث',
                  footer: {
                    selectText: 'للتحديد',
                    navigateText: 'للتنقل',
                    closeText: 'للإغلاق'
                  }
                }
              }
            }
          }
        }
      },

      // Social links
      socialLinks: [
        { icon: 'github', link: 'https://github.com/restopos/restopos' },
        { icon: 'twitter', link: 'https://twitter.com/restopos' },
        { icon: 'discord', link: 'https://discord.gg/restopos' },
        { icon: 'linkedin', link: 'https://linkedin.com/company/restopos' }
      ]
    },

    // Simplified markdown configuration
    markdown: {
      theme: {
        light: 'github-light',
        dark: 'github-dark'
      },
      lineNumbers: true,
      container: {
        tipLabel: 'TIP',
        warningLabel: 'WARNING',
        dangerLabel: 'DANGER',
        infoLabel: 'INFO',
        detailsLabel: 'Details'
      },
      config: (md) => {
        // Use only essential and working plugins
        md.use(tabsMarkdownPlugin)
        md.use(markdownItContainer, 'tip')
        md.use(markdownItContainer, 'warning')
        md.use(markdownItContainer, 'danger')
        md.use(markdownItContainer, 'info')
        md.use(markdownItContainer, 'details')
        md.use(markdownItAnchor, {
          permalink: markdownItAnchor.permalink.headerLink()
        })

        // Custom containers
        md.use(markdownItContainer, 'code-group', {
          render: (tokens, idx) => {
            const token = tokens[idx]
            if (token.nesting === 1) {
              return '<div class="code-group">\n'
            } else {
              return '</div>\n'
            }
          }
        })

        md.use(markdownItContainer, 'api-endpoint', {
          render: (tokens, idx) => {
            const token = tokens[idx]
            if (token.nesting === 1) {
              return '<div class="api-endpoint">\n'
            } else {
              return '</div>\n'
            }
          }
        })
      }
    },

    // Vite configuration
    vite: {
      define: {
        __VUE_OPTIONS_API__: false
      },
      server: {
        host: true,
        fs: {
          allow: ['..', '../..']
        }
      },
      build: {
        minify: 'terser',
        chunkSizeWarningLimit: 1000
      }
    },

    // Build hooks for RSS feed
    buildEnd: async (config) => {
      // Generate RSS feed
      const feed = new Feed({
        title,
        description,
        id: baseUrl,
        link: baseUrl,
        language: 'en',
        favicon: `${baseUrl}/favicon.ico`,
        copyright: 'Copyright © 2024 RestoPos Team',
        generator: 'VitePress'
      })

      // Add recent updates to feed
      feed.addItem({
        title: 'RestoPos Documentation Updated',
        id: `${baseUrl}/updates/latest`,
        link: `${baseUrl}/resources/changelog`,
        description: 'Latest updates to RestoPos documentation',
        date: new Date()
      })

      // Write RSS feed
      const rssPath = resolve(config.outDir, 'feed.xml')
      const rssContent = feed.rss2()
      require('fs').writeFileSync(rssPath, rssContent)
    },

    // Clean URLs
    cleanUrls: true,

    // Last updated
    lastUpdated: true,

    // Ignore dead links during build
    ignoreDeadLinks: false,

    // Enable experimental features
    experimental: {
      hmrPartialReload: true
    }
  })
