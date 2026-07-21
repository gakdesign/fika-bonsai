# Glacial Indifference

Glacial Indifference is not a Google Font, so it's self-hosted instead of loaded from a CDN.

Drop the licensed font files into this folder using these exact names (referenced by the `@font-face` rules in `assets/css/core/additions.css`):

- `GlacialIndifference-Regular.woff2`
- `GlacialIndifference-Regular.woff`
- `GlacialIndifference-Bold.woff2`
- `GlacialIndifference-Bold.woff`

If you only have `.otf`/`.ttf` files, convert them to `.woff2`/`.woff` first (e.g. via [Transfonter](https://transfonter.org/) or `fonttools`) — browsers won't render `.otf` reliably for web use and it's a much larger download.

Until these files are present, `--font-body` will fall back to `system-ui, sans-serif` (see `additions.css`).
