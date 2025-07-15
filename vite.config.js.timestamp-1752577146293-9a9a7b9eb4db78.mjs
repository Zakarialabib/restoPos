// vite.config.js
import { defineConfig } from "file:///home/project/node_modules/vite/dist/node/index.js";
import laravel from "file:///home/project/node_modules/laravel-vite-plugin/dist/index.js";
import autoprefixer from "file:///home/project/node_modules/autoprefixer/lib/autoprefixer.js";
import tailwindcss from "file:///home/project/node_modules/tailwindcss/lib/index.js";
import postcssImport from "file:///home/project/node_modules/postcss-import/index.js";
import postcssNesting from "file:///home/project/node_modules/postcss-nesting/dist/index.mjs";
import { resolve } from "path";
var __vite_injected_original_dirname = "/home/project";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/js/app.js",
        "resources/js/admin.js",
        "resources/css/app.css"
      ],
      refresh: [
        "resources/views/**/*.blade.php",
        "resources/js/**/*.js",
        "app/Livewire/**/*.php",
        "app/Http/Controllers/**/*.php"
      ]
    })
  ],
  resolve: {
    alias: {
      "@": resolve(__vite_injected_original_dirname, "resources"),
      "~": resolve(__vite_injected_original_dirname, "node_modules")
    }
  },
  css: {
    postcss: {
      plugins: [
        postcssImport(),
        postcssNesting(),
        tailwindcss(),
        autoprefixer()
      ]
    },
    devSourcemap: true
  },
  build: {
    sourcemap: process.env.NODE_ENV === "development",
    chunkSizeWarningLimit: 1e3
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvaG9tZS9wcm9qZWN0XCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCIvaG9tZS9wcm9qZWN0L3ZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9ob21lL3Byb2plY3Qvdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJztcbmltcG9ydCBsYXJhdmVsIGZyb20gJ2xhcmF2ZWwtdml0ZS1wbHVnaW4nO1xuaW1wb3J0IGF1dG9wcmVmaXhlciBmcm9tICdhdXRvcHJlZml4ZXInO1xuaW1wb3J0IHRhaWx3aW5kY3NzIGZyb20gJ3RhaWx3aW5kY3NzJztcbmltcG9ydCBwb3N0Y3NzSW1wb3J0IGZyb20gJ3Bvc3Rjc3MtaW1wb3J0JztcbmltcG9ydCBwb3N0Y3NzTmVzdGluZyBmcm9tICdwb3N0Y3NzLW5lc3RpbmcnO1xuaW1wb3J0IHsgcmVzb2x2ZSB9IGZyb20gJ3BhdGgnO1xuXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xuICAgIHBsdWdpbnM6IFtcbiAgICAgICAgbGFyYXZlbCh7XG4gICAgICAgICAgICBpbnB1dDogW1xuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvanMvYXBwLmpzJyxcbiAgICAgICAgICAgICAgICAncmVzb3VyY2VzL2pzL2FkbWluLmpzJyxcbiAgICAgICAgICAgICAgICAncmVzb3VyY2VzL2Nzcy9hcHAuY3NzJyxcbiAgICAgICAgICAgIF0sXG4gICAgICAgICAgICByZWZyZXNoOiBbXG4gICAgICAgICAgICAgICAgJ3Jlc291cmNlcy92aWV3cy8qKi8qLmJsYWRlLnBocCcsXG4gICAgICAgICAgICAgICAgJ3Jlc291cmNlcy9qcy8qKi8qLmpzJyxcbiAgICAgICAgICAgICAgICAnYXBwL0xpdmV3aXJlLyoqLyoucGhwJyxcbiAgICAgICAgICAgICAgICAnYXBwL0h0dHAvQ29udHJvbGxlcnMvKiovKi5waHAnLFxuICAgICAgICAgICAgXSxcbiAgICAgICAgfSksXG4gICAgXSxcbiAgICByZXNvbHZlOiB7XG4gICAgICAgIGFsaWFzOiB7XG4gICAgICAgICAgICAnQCc6IHJlc29sdmUoX19kaXJuYW1lLCAncmVzb3VyY2VzJyksXG4gICAgICAgICAgICAnfic6IHJlc29sdmUoX19kaXJuYW1lLCAnbm9kZV9tb2R1bGVzJyksXG4gICAgICAgIH0sXG4gICAgfSxcbiAgICBjc3M6IHtcbiAgICAgICAgcG9zdGNzczoge1xuICAgICAgICAgICAgcGx1Z2luczogW1xuICAgICAgICAgICAgICAgIHBvc3Rjc3NJbXBvcnQoKSxcbiAgICAgICAgICAgICAgICBwb3N0Y3NzTmVzdGluZygpLFxuICAgICAgICAgICAgICAgIHRhaWx3aW5kY3NzKCksXG4gICAgICAgICAgICAgICAgYXV0b3ByZWZpeGVyKCksXG4gICAgICAgICAgICBdLFxuICAgICAgICB9LFxuICAgICAgICBkZXZTb3VyY2VtYXA6IHRydWUsXG4gICAgfSxcbiAgICBidWlsZDoge1xuICAgICAgICBzb3VyY2VtYXA6IHByb2Nlc3MuZW52Lk5PREVfRU5WID09PSAnZGV2ZWxvcG1lbnQnLFxuICAgICAgICBjaHVua1NpemVXYXJuaW5nTGltaXQ6IDEwMDAsXG4gICAgfSxcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUF5TixTQUFTLG9CQUFvQjtBQUN0UCxPQUFPLGFBQWE7QUFDcEIsT0FBTyxrQkFBa0I7QUFDekIsT0FBTyxpQkFBaUI7QUFDeEIsT0FBTyxtQkFBbUI7QUFDMUIsT0FBTyxvQkFBb0I7QUFDM0IsU0FBUyxlQUFlO0FBTnhCLElBQU0sbUNBQW1DO0FBUXpDLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFNBQVM7QUFBQSxJQUNMLFFBQVE7QUFBQSxNQUNKLE9BQU87QUFBQSxRQUNIO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxNQUNKO0FBQUEsTUFDQSxTQUFTO0FBQUEsUUFDTDtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxPQUFPO0FBQUEsTUFDSCxLQUFLLFFBQVEsa0NBQVcsV0FBVztBQUFBLE1BQ25DLEtBQUssUUFBUSxrQ0FBVyxjQUFjO0FBQUEsSUFDMUM7QUFBQSxFQUNKO0FBQUEsRUFDQSxLQUFLO0FBQUEsSUFDRCxTQUFTO0FBQUEsTUFDTCxTQUFTO0FBQUEsUUFDTCxjQUFjO0FBQUEsUUFDZCxlQUFlO0FBQUEsUUFDZixZQUFZO0FBQUEsUUFDWixhQUFhO0FBQUEsTUFDakI7QUFBQSxJQUNKO0FBQUEsSUFDQSxjQUFjO0FBQUEsRUFDbEI7QUFBQSxFQUNBLE9BQU87QUFBQSxJQUNILFdBQVcsUUFBUSxJQUFJLGFBQWE7QUFBQSxJQUNwQyx1QkFBdUI7QUFBQSxFQUMzQjtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
