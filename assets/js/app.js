/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('./social');
require('../css/app.css');

const imagesContext = require.context('../images', true, /\.(png|jpg|jpeg|gif|ico|svg|webp)$/);
const fontsContext = require.context('../fonts', true, /\.(ttf|woff|woff2)$/);
imagesContext.keys().forEach(imagesContext);
fontsContext.keys().forEach(fontsContext);

