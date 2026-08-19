/**
 * Alpine, bundled.
 *
 * The site used to pull Alpine from unpkg on every page load, at a version
 * range (3.x.x) that had to be resolved before the file could even be fetched.
 * That is a DNS lookup, a TCP connection, a TLS handshake and a redirect to
 * reach a library that is 15KB.
 *
 * Deliberately not resources/js/app.js, which also starts Alpine: that file
 * carries the navbar scroll and mobile menu handlers, and those same handlers
 * are already inline in layouts/navigation.blade.php. Bundling app.js would
 * bind each of them twice. This entry does the one thing the public site
 * actually needs from a bundle.
 */
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
