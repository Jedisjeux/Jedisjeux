// @ts-check
import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';

export default defineConfig({
    // Published on GitHub Pages with a custom domain
    site: 'https://docs.jedisjeux.net',
    integrations: [
        starlight({
            title: 'Jedisjeux',
            favicon: '/favicon.png',
            customCss: ['./src/styles/custom.css'],
            components: {
                SiteTitle: './src/components/SiteTitle.astro',
            },
            head: [
                { tag: 'link', attrs: { rel: 'preconnect', href: 'https://fonts.googleapis.com' } },
                { tag: 'link', attrs: { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: true } },
                { tag: 'link', attrs: { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@700&display=swap' } },
            ],
            defaultLocale: 'root',
            locales: {
                root: { label: 'Français', lang: 'fr' },
            },
            social: [
                { icon: 'github', label: 'GitHub', href: 'https://github.com/Jedisjeux/jedisjeux' },
            ],
            editLink: {
                baseUrl: 'https://github.com/Jedisjeux/jedisjeux/edit/main/docs/',
            },
            sidebar: [
                {
                    label: 'Guide utilisateur',
                    items: [
                        {
                            label: 'Jeux',
                            items: [
                                'user-guide/games',
                                'user-guide/games/reprint',
                                'user-guide/games/translation',
                                'user-guide/games/same-collection',
                                'user-guide/games/expansion',
                            ],
                        },
                        {
                            label: 'Articles',
                            items: [
                                'user-guide/articles/game-review',
                                'user-guide/articles/blocks',
                            ],
                        },
                    ],
                },
                {
                    label: 'Architecture',
                    items: [{ autogenerate: { directory: 'book/architecture' } }],
                },
                {
                    label: 'API REST',
                    items: [{ autogenerate: { directory: 'api' } }],
                },
                {
                    label: 'Exploitation',
                    items: ['server', 'deployment'],
                },
            ],
        }),
    ],
});
