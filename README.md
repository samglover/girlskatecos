# Girl Skate Cos.

[GirlSkateCos.](https://girlskatecos.com/) is a directory of skateboard brands owned by women.

The website is built with [Jekyll](https://jekyllrb.com/) and published with GitHub Pages from the `main` branch.

## Brands

Brands are collected in the `_brands` folder, one Markdown file per brand. The front matter may contain:

- `title`: The brand name.
- `logo`: Filename of the brand's logo in the `assets/img/logos` folder.
- `owners`: A single name, a list of names, or a short description (e.g., for a collective).
- `website_url`, `instagram_url`: Optional. Hidden if the brand is closed.
- `product_types`: Any of `Skateboards & parts`, `Clothing & accessories`, `Stickers`, `Art`, `Zine`, or `Lessons`.
- `redirect_from`: Optional. Old URLs that should redirect to the brand page.
- `closed: true`: Include if the brand has closed.

Anything below the front matter is shown on the brand page as the page content.

## Embed videos

Responsive video embedding for YouTube and Vimeo is based on [https://github.com/nathancy/jekyll-embed-video](https://github.com/nathancy/jekyll-embed-video)

`{% include youtube.html id="<youtubeId>" %}`
`{% include vimeo.html id="<vimeoId>" %}`

(The quotation marks are necessary!)