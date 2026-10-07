# Workshop waiting list — manual acceptance checklist

Code: `inc/waiting-list.php`, `assets/js/workshop-waiting-list.js`
Client: Fika Exeter
Tested by:
Date:
Environment (staging URL):

## Setup
- [ ] ACF → Field Groups → Sync "Theme Settings" (new Workshop Waiting List Email field)
- [ ] Theme Settings → Workshop Waiting List Email set to a test inbox
- [ ] A product in the Workshops category, Manage stock ticked, stock set to 0 (out of stock)
- [ ] A non-workshop product, also out of stock (for the negative check)

## Visibility
- [ ] Sold-out workshop product → Sold Out badge + "Join the waiting list" form shown
- [ ] Same product with stock > 0 → form not shown, booking form shown instead
- [ ] Sold-out non-workshop product → no waiting list form

## Success path (JS on)
- [ ] Valid email + consent ticked → "Thanks — you're on the waiting list…" message, form hides, page doesn't reload
- [ ] Notification received at the Theme Settings inbox: subject "[Site] Waiting list: {workshop}", body has workshop title, When, email, signed-up time, count, product + edit links
- [ ] Replying to the notification goes to the visitor's email (Reply-To)
- [ ] Product edit screen → "Waiting list" box lists the email with date/time
- [ ] Same email again → "You're already on the waiting list…", no second notification, no duplicate in the box

## Failure paths (JS on)
- [ ] Invalid email (e.g. `test@`) → browser validation / "Please enter a valid email address."
- [ ] Consent unticked → browser validation / consent message; nothing stored
- [ ] 6th attempt within an hour from the same IP → "Too many attempts…"
- [ ] Theme Settings email left blank → notification goes to Settings → General admin email

## No-JS fallback
- [ ] Disable JS → submit form → redirected back to product at #workshop-waitlist with the success message
- [ ] Invalid email with JS off → redirected back with the error message

## Admin
- [ ] "Email everyone (BCC)" opens mail client with all addresses in BCC
- [ ] Tick "Clear the waiting list when I update" → Update → list emptied

## GDPR
- [ ] Tools → Export Personal Data for a test email → export includes "Workshop waiting lists" entries
- [ ] Tools → Erase Personal Data for that email → removed from every product's waiting list
- [ ] Settings → Privacy → Policy Guide shows the "Workshop waiting list" suggested text; added to the privacy policy page
- [ ] Privacy policy link appears next to the consent checkbox (requires a privacy policy page set in Settings → Privacy)

## Caching
- [ ] With WP Rocket / SG cache on, submit from a cached (logged-out) product page → still succeeds (nonce valid within cache lifespan)
