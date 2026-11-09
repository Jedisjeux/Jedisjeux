# Jedisjeux documentation

The documentation of Jedisjeux, built with [Astro Starlight](https://starlight.astro.build/).
The pages are Markdown files in `src/content/docs/`, and the sidebar is set in `astro.config.mjs`.

## Run it locally

```sh
castor docs:open
```

It serves the documentation with live reload on http://localhost:4321 and opens it in the browser.
Node runs inside Docker, so it is not needed on your machine.

## Build it

```sh
castor docs:build
```

The static site is generated in `docs/dist/`.

Without castor, run `npm install` then `npm run dev` or `npm run build` in `docs/`.

## Publication

The `Docs` GitHub workflow builds the documentation on the pull requests that change `docs/`,
and publishes it on GitHub Pages (https://docs.jedisjeux.net) from `main`.

## Retake the screenshots

The screenshots of the user guide are taken from the local stack (https://jedisjeux.test) with the fixtures loaded
(`castor app:db:fixtures`). Google Chrome must be installed.

```sh
npm run screenshots
```

The script logs in with the `admin@example.com` fixture account, fills some forms to show an example (it never submits them)
and writes the images in `src/assets/screenshots/`. Set `BASE_URL` to use another host.
