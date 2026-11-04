require "fileutils"
require "vips"

# Makes small copies of the site logo and brand logos at the sizes they're shown,
# plus 2x copies for high-resolution screens, and gives templates the srcset and
# dimensions.
#
# Each copy is written to a folder named for its width next to the original,
# e.g. assets/img/logos/210/brujas.png. Copies are cached in .jekyll-cache/logos
# and only rebuilt when the original changes.
module LogoImages
  # The header logo is shown 3lh (93.75px) tall and is square.
  SITE_LOGO_WIDTHS = [94, 188]
  BRAND_LOGO_DIR = "assets/img/logos"
  BRAND_LOGO_WIDTHS = [210, 420]

  class Generator < Jekyll::Generator
    priority :high

    def generate(site)
      @site = site
      @cache_root = File.join(site.source, ".jekyll-cache", "logos")

      if site.config["logo"]
        add_images(site.config, "logo", site.config["logo"].delete_prefix("/"), SITE_LOGO_WIDTHS)
      end

      site.collections["brands"].docs.each do |brand|
        next unless brand.data["logo"]
        add_images(brand.data, "logo", File.join(BRAND_LOGO_DIR, brand.data["logo"]), BRAND_LOGO_WIDTHS)
      end
    end

    private

    # Resizes the image at `path` (relative to the site source) to each width and
    # sets `<prefix>_src`, `<prefix>_srcset`, `<prefix>_width` and `<prefix>_height`
    # on `data`. The first width is the default src and sets the dimensions.
    def add_images(data, prefix, path, widths)
      source = File.join(@site.source, path)
      return unless File.exist?(source)

      dir = File.dirname(path)
      name = File.basename(path)
      srcset = []

      widths.each do |width|
        resized_dir = File.join(dir, width.to_s)
        output = File.join(@cache_root, resized_dir, name)
        resize(source, output, width) if stale?(source, output)

        @site.static_files << Jekyll::StaticFile.new(@site, @cache_root, resized_dir, name)
        resized = Vips::Image.new_from_file(output)

        # Images narrower than a target width come out the same size more than once.
        unless srcset.any? { |entry| entry.end_with?(" #{resized.width}w") }
          srcset << "#{@site.baseurl}/#{resized_dir}/#{name} #{resized.width}w"
        end

        next unless width == widths.first
        data["#{prefix}_src"] = "/#{resized_dir}/#{name}"
        data["#{prefix}_width"] = resized.width
        data["#{prefix}_height"] = resized.height
      end

      data["#{prefix}_srcset"] = srcset.join(", ")
    end

    def stale?(source, output)
      !File.exist?(output) || File.mtime(output) < File.mtime(source)
    end

    # Shrinks to the given width, never enlarging, and keeps the original format.
    def resize(source, output, width)
      FileUtils.mkdir_p(File.dirname(output))
      image = Vips::Image.thumbnail(source, width, height: 10_000_000, size: :down)
      options = output.match?(/\.(jpe?g|webp)\z/i) ? { Q: 85 } : {}
      image.write_to_file(output, **options)
    end
  end
end
