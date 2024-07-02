# IIIF Viewers (module for Omeka S)

[IIIF Viewers] is a module to show IIIF Manifest URI icon and viewers.

![viewer](asset/screen/viewer.png)

![setting](asset/screen/setting.png)

## Installation

See general end user documentation for [installing a module].

### From the zip

Download the last release [IiifViewers.zip] from the list of releases, and
uncompress it in the `modules` directory.

### From the source and for development

If the module was installed from the source, rename the name of the folder of
the module to `IiifViewers`, go to the root of the module, and run:

```sh
composer install --no-dev
```

### Compilation of Universal Viewer

The Universal Viewer is provided as a compressed file in order to be installed quickly with composer. The compressed file is the vanilla version that is built with default options.

So, you need to compile Universal Viewer only for development.

For v4, in a temp directory:

```
cd /tmp
git clone https://github.com/UniversalViewer/universalviewer
cd universalviewer
npm install
npm run build
```

Then, the content of the directory "dist" is copied in the directory "asset/vendor/uv" of the module.

### Update Mirador

Download the latest version of Mirador and copy it to the module directory.

```
cd ./asset/vendor/mirador
wget -O mirador.min.js https://unpkg.com/mirador@latest/dist/mirador.min.js
```

## Warning

Use it at your own risk.

It’s always recommended to backup your files and your databases and to check
your archives regularly so you can roll back if needed.

## Troubleshooting

See online issues on the [module issues] page on GitHub.

## Contributors

- Satoru Nakamura, 2021- (see [nakamura196] on GitHub)
- National Institute of Japanese Literature, 2021- (see [nijl])

[IIIF Viewers]: https://github.com/nakamura196/Omeka-S-module-IiifViewers
[Omeka S]: https://omeka.org/s
[installing a module]: http://dev.omeka.org/docs/s/user-manual/modules/#installing-modules
[IiifViewers.zip]: https://github.com/nakamura196/Omeka-S-module-IiifViewers/releases
[module issues]: https://github.com/nakamura196/Omeka-S-module-IiifViewers/issues
[nakamura196]: https://github.com/nakamura196 "Satoru Nakamura"
[nijl]: https://www.nijl.ac.jp/en/ "National Institute of Japanese Literature"

## php-cs

```bash
./vendor/bin/php-cs-fixer fix
```

## Viewers

| Name                 | URL                                                                       | Icon          |
| -------------------- | ------------------------------------------------------------------------- | ------------- |
| Mirador              | https://projectmirador.org/embed/?iiif-content=                           | mirador3.svg  |
| Universal Viewer     | https://uv-v3.netlify.app/#?manifest=                                     | uv.jpg        |
| Annona               | https://ncsu-libraries.github.io/annona/tools/#/display?url=              | annoa.png     |
| Clover               | https://samvera-labs.github.io/clover-iiif/docs/viewer/demo?iiif-content= | clover.png    |
| Glycerine Viewer     | https://demo.viewer.glycerine.io/viewer?iiif-content=                     | glycerine.jpg |
| IIIF Curation Viewer | http://codh.rois.ac.jp/software/iiif-curation-viewer/demo/?manifest=      | icp-logo.svg  |
| Image Annotator      | https://www.kanzaki.com/works/2016/pub/image-annotator?u=                 | ia-logo.png   |
| TIFY                 | https://tify.rocks/?manifest=                                             | tify-logo.svg |
