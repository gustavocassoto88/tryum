on_sprite_saved do |filename|
    if File.exists?(filename)
        FileUtils.cp filename, filename.gsub(%r{-s[a-z0-9]{10}\.png$}, '.png')
        FileUtils.rm_rf(filename)
    end
end

on_stylesheet_saved do |filename|
    if File.exists?(filename)
        css = File.read filename
        File.open(filename, 'w+') do |f|
            f << css.gsub(%r{-s[a-z0-9]{10}\.png}, '.png')
        end
    end
end

http_path = "/"
css_dir = "arquivos/default/css"
sass_dir = "arquivos/default/scss"
images_dir = "arquivos/default/img"
http_generated_images_path = "../img"
javascripts_dir = "arquivos/default/js"

output_style = :compressed