// Retakes the screenshots of the user guide from the local stack with the fixtures loaded.
// Usage: npm run screenshots (uses the installed Google Chrome, BASE_URL defaults to https://jedisjeux.test)
// The forms are filled to show an example but never submitted.
import { chromium } from 'playwright-core';

const OUT = new URL('../src/assets/screenshots/', import.meta.url).pathname;
const BASE_URL = process.env.BASE_URL ?? 'https://jedisjeux.test';

async function open() {
  const browser = await chromium.launch({ channel: 'chrome', headless: true });
  const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1280, height: 800 }, deviceScaleFactor: 2, locale: 'fr-FR' });
  const page = await context.newPage();
  await page.goto(BASE_URL + '/login');
  await page.fill('input[placeholder="Nom d\'utilisateur ou email"]', 'admin@example.com');
  await page.fill('input[type=password]', 'admin');
  await Promise.all([page.waitForNavigation(), page.press('input[type=password]', 'Enter')]);
  return { browser, page };
}
async function clean(page) {
  await page.addStyleTag({ content: '.sf-toolbar,.sf-minitoolbar,[id^=sfwdt]{display:none!important}' });
}

const { browser, page } = await open();
const noTooltips = () => page.addStyleTag({ content: '.tooltip{display:none!important}' });
const box = async (loc, pad = 16) => { const b = await loc.boundingBox(); return { x: Math.max(0, b.x - pad), y: Math.max(0, b.y - pad), width: b.width + 2 * pad, height: b.height + 2 * pad }; };
const union = (a, b) => { const x = Math.min(a.x, b.x), y = Math.min(a.y, b.y); return { x, y, width: Math.max(a.x + a.width, b.x + b.width) - x, height: Math.max(a.y + a.height, b.y + b.height) - y }; };

// 1 & 2: game list actions
await page.goto(BASE_URL + '/admin/products/'); await clean(page); await noTooltips();
const cell = page.locator('table tbody tr').first().locator('td').last();
await cell.locator('[data-bs-title="Plus"]').click();
await page.waitForTimeout(300); await page.mouse.move(5, 5);
const table = page.locator('table').first();
const tb = await table.boundingBox();
const productId = (await cell.locator('a[href$="/edit"]').getAttribute('href')).match(/products\/(\d+)/)[1];
const menu = cell.locator('.dropdown-menu.show').first();
await page.screenshot({ path: OUT + 'games/actions-menu.png', clip: union({ x: tb.x, y: tb.y, width: tb.width, height: 260 }, await box(menu)) });
await cell.locator('button:has-text("Gérer les articles")').evaluate(b => { b.classList.add('show'); const m = b.nextElementSibling; m.classList.add('show'); m.style.cssText = 'position:absolute; inset:auto 0 auto auto; top:100%'; });
await page.waitForTimeout(300);
await page.screenshot({ path: OUT + 'articles/game-review-menu.png', clip: union({ x: tb.x, y: tb.y, width: tb.width, height: 260 }, await box(cell.locator('.dropdown-menu.show').last())) });

// 3: game review form, blocks tab
await page.goto(BASE_URL + `/admin/articles/reviews/${productId}/new`); await clean(page);
await page.locator('[data-bs-toggle="tab"]:has-text("Blocs")').click(); await page.waitForTimeout(1000);
await page.screenshot({ path: OUT + 'articles/game-review-form.png' });

// 4: associations
await page.goto(BASE_URL + `/admin/products/${productId}/edit`); await clean(page);
await page.locator('[data-bs-toggle="tab"]:has-text("Associations")').click(); await page.waitForTimeout(400);
await page.fill('#sylius_product_associations_expansion', 'extension_1,extension_2');
await page.mouse.move(5, 5);
await page.screenshot({ path: OUT + 'games/associations.png', clip: { x: 248, y: 60, width: 1032, height: 600 } });

// 5 & 6: article blocks
// The first article of the list, with at least one block
await page.goto(BASE_URL + '/admin/articles/');
await page.goto(BASE_URL + await page.locator('table tbody tr a[href$="/edit"]').first().getAttribute('href')); await clean(page);
await page.locator('[data-bs-toggle="tab"]:has-text("Blocs")').click(); await page.waitForTimeout(1500);
const firstBlock = page.locator('#tab-blocks label:has-text("Jeu lié")').first().locator('xpath=ancestor::div[contains(@class,"card")][1]');
const field = page.locator('#tab-blocks [data-app-autocomplete]').first();
const ts = field.locator('[data-autocomplete=search]');
await ts.click(); await ts.pressSequentially('Alch', { delay: 80 });
await page.waitForTimeout(1500);
const opt = field.locator('[data-autocomplete=results] .dropdown-item').first();
await opt.click().catch(() => {});
await page.waitForTimeout(300); await page.mouse.move(5, 5);
await firstBlock.scrollIntoViewIfNeeded();
await firstBlock.screenshot({ path: OUT + 'articles/block-linked-game.png' });
const toolbar = page.locator('#tab-blocks .cke_top').first();
await toolbar.scrollIntoViewIfNeeded();
await toolbar.locator('.cke_button__source').evaluate(b => { b.style.outline = '3px solid #e8590c'; b.style.outlineOffset = '1px'; b.style.borderRadius = '3px'; });
await page.screenshot({ path: OUT + 'articles/ckeditor-source.png', clip: await box(toolbar, 12) });
await browser.close();
