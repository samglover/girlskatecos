# Keep closed brands out of the jekyll-sitemap output.
Jekyll::Hooks.register :site, :post_read do |site|
  site.collections["brands"].docs.each do |brand|
    brand.data["sitemap"] = false if brand.data["closed"]
  end
end
