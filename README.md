# Studentspex – WordPress Theme

Block theme for Linköping Studentspex. All content is edited directly on the page using blocks. Productions are managed as their own content type, and the newsletter is built in. The theme requires no additional plugins.

**Requirements:** WordPress 6.6 or later (tested on 7.1), PHP 7.4 or later.

## Install

1. Appearance → Themes → Add New Theme → Upload Theme → choose `studentspex.zip` → Install → Activate.
2. Go to **Spexet → Get started** and click **Create all**. This creates:
   - the pages Home, Next show, Search the show, About the show, Contact and News
   - the main menu
   - the homepage and posts page
   - next year's production (as a draft), selected as current
   - if checked: the 47 previous productions from 1981–2026, with title, year and description
3. **Spexet → Settings:** add the link to the application form, check social media and ticket email settings, and enter a sender address for the newsletter. Use an address on your own domain.
4. **Recommended:** install an SMTP plugin, such as *WP Mail SMTP*, so the confirmation emails do not end up in spam.

## What you edit where

| What | Where |
|---|---|
| Text, headings, cards, groups, questions, contacts, direction | Directly on the page (Pages → select page). Each section is a block. |
| Next production: title, date, prices, ticket link, synopsis, poster, **cover image**, **this year's Lisse** | **Productions** → current production → panels in the right sidebar |
| Which production is current | Spexet → Settings |
| Sponsors ("Thanks to"), footer, header, logo | Appearance → Editor → Patterns → Footer / Header |
| Menu | Appearance → Editor → Navigation |
| Colors and fonts | Appearance → Editor → Styles |
| News | Posts, just like usual |
| Subscribers | Spexet → Newsletter |

The Studentspex blocks are available in the block selector under the **Studentspex** category. Ready-made full pages are available under Patterns → *Studentspex – full pages*.

## A new show year, step by step

1. **Productions → Add new:** enter the title, year, subtitle, synopsis, dates and prices.
2. Add images:
   - **Cover image:** landscape, at least 2000 px wide. It is shown behind the homepage hero.
   - **This year's Lisse:** PNG with transparent background. Replaces the default Lisse.
   - **Poster.**
3. **Spexet → Settings → Next production:** select the new production. Last year's production moves automatically to Old productions.
4. When tickets are released: fill in **Link to ticket purchase**. The "Buy tickets" button is then activated.

## Newsletter

- **Subscription:** the visitor enters their address and checks consent. A confirmation email is sent, and the address is only added when the link in the email is clicked (double opt-in).
- **Maintenance:** unconfirmed sign-ups are deleted after 30 days.
- **Personal data:** export and deletion of a person's data works via Tools → Export/Delete Personal Data. A suggested privacy text is available under Settings → Privacy.
- **Dispatch:** Spexet → Newsletter → **Export confirmed (CSV)**. Send the email as a mass mailing (recipients as BCC) or via a mail merge. Include each recipient's `unsubscribe_url` in the email.
- **Abuse protection:** nonce, a hidden honeypot field, and a maximum of 5 attempts per 10 minutes and IP address. The limit can be changed with the filter `studentspex_nl_rate_limit`.

## For developers

The block source code is in `src/` (not included in the zip file).

```
npm install
npm run build    # src/ → build/
npm run start    # rebuilds on changes
npm run lint:js
npm run zip      # creates ../dist/studentspex.zip
```

PHP follows WordPress Coding Standards (`.phpcs.xml.dist`). Run `phpcs` with WPCS 3 installed.

- **Patterns and pages:** the patterns in `patterns/` are written in PHP using the helper functions in `inc/pattern-helpers.php`. This gives the exact same block markup as the editor saves.
- **Block definitions:** all blocks are dynamic (`render.php`). `block.json` is generated from `tools/blocks.spec.mjs` with `node tools/gen-block-json.mjs`.
- **Translation:** the theme text is written in Swedish and prepared for translation (text domain `studentspex`).
