# Invoice Shelf - White Label Module

Adds ability to customise your InvoiceShelf instance with logo and brand color.

![Preview of Invoice Shelf, White Label Settings page](./preview.png)

## Installation

1. Download the zip with pre-built module from the latest release in this repo. Alternatively you can build from source, for that follow the steps in the [development](#development) section.
2. Create `/Modules/` fir in your server InvoiceShelf project root. **NOTE:** make sure it's capitalised like in example, it's not a typo.
3. Upload the `WhiteLabel.zip` into the newly created `/Modules/` dir.
4. unzip it.
5. in your server InvoiceShelf project `/` root dir, run `php artisan install:module WhiteLabel 1.0.0`.

## Development

This is a step by step guide on how to get started, with development.

### Prerequisites

1. nvm (optional)
2. Node JS: `20 LTS`
3. `yarn`

### Steps

1. Clone the [InvoiceShelf repo](https://github.com/InvoiceShelf/InvoiceShelf) - needed for the admin components. In the future it's likely to be just a node package and you wont need this step.
2. Create `Modules/` dir, in the root dir.
3. Clone this repo into `WhiteLabel/` dir inside `/Modules/`.
4. Initialise `node_modules` by running `yarn` inside `/` of the InvoiceShelf repo.
5. Initialise `node_modules` by running `yarn` inside `/Modules/WhiteLabel/` of this repo.
6. Make the changes you wish to make.
7. Run `yarn build`, new `/Modules/WhiteLabel/dist/` dir will be created with built `css` and `js` bundles.
8. Upload the contents of `/Modules/WhiteLabel/` to your server with InvoiceShelf. Either use SFTP or zip up the folder.
9. Create `Modules/` dir inside your server InvoiceShelf `/` root dir of the project.
10. `upload`/`unzip`/`untar` the `WhiteLabel/` inside the `Modules/` dir.
11. Return back to server InvoiceShelf `/` root dir, and run `php artisan install:module WhiteLabel 1.0.0` in the `/` root dir of the InvoiceShelf server.

## Troubleshooting

- If your uploaded logo isn't showing up or uploading you may need to again fix the `/storage/` permissions by running `chmod -R 775 storage`.

## Copyright

    Copyright (C) 2022-2023 <Nicolas Widart> n.widart@gmail.com
    Copyright (C) 2024 <Rihards Simanovics> rihards.s@griffin-web.studio
