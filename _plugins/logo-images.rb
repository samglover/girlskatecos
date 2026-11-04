require "fileutils"
require "vips"

# Make small copies of each brand logo for the homepage stickers, plus a 2x copy
# for high-resolution screens, and give templates the srcset and dimensions.
#
# Resized files are cached in .jekyll-cache/logos and only rebuilt when the
# original logo changes.
module LogoImages
  LOGO_DIR = "assets/img/logos"
  WIDTHS = [210, 420]

  class Generator < Jekyll::Generator
    priority :high

    def generate(site)
      cache_root = File.join(site.source, ".jekyll-cache", "logos")

      site.collections["brands"].docs.each do |brand|
        logo = brand.data["logo"]
        next unless logo

        source = File.join(site.source, LOGO_DIR, logo)
        next unless File.exist?(source)

        original = Vips::Image.new_from_file(source)
        srcset = []

        WIDTHS.each do |width|
          dir = File.join(LOGO_DIR, width.to_s)
          output = File.join(cache_root, dir, logo)
          resize(source, output, width) if stale?(source, output)

          site.static_files << Jekyll::StaticFile.new(site, cache_root, dir, logo)
          resized = Vips::Image.new_from_file(output)
          # Logos narrower than a target width come out the same size more than once.
          unless srcset.any? { |entry| entry.end_with?(" #{resized.width}w") }
            srcset << "#{site.baseurl}/#{dir}/#{logo} #{resized.width}w"
          end

          # The smallest size is the default src and sets the width/height attributes.
          next unless width == WIDTHS.first
          brand.data["logo_src"] = "/#{dir}/#{logo}"
          brand.data["logo_width"] = resized.width
          brand.data["logo_height"] = resized.height
        end

        brand.data["logo_srcset"] = srcset.join(", ")
      end
    end

    private

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
