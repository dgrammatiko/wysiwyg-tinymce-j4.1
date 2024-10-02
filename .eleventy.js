const pluginSyntaxHighlight = require("@11ty/eleventy-plugin-syntaxhighlight");
const eleventyNavigationPlugin = require("@11ty/eleventy-navigation");
const Nunjucks = require('nunjucks');
const anchor = require("markdown-it-anchor");
// const htmlmin = require("html-minifier");
// const codepenIt = require("11ty-to-codepen");
const Image = require("@11ty/eleventy-img");

async function imageShortcode(src, alt, sizes) {
  let metadata = await Image(src, {
    widths: [300, 600, 1000],
    formats: ["avif", "jpeg"],
    outputDir: "./_site/images/",
    urlPath: "/images/"
  });

  let imageAttributes = {
    alt,
    sizes,
    loading: "lazy",
    decoding: "async",
  };

  // You bet we throw an error on missing alt in `imageAttributes` (alt="" works okay)
  return Image.generateHTML(metadata, imageAttributes);
}

// let nunjucksEnvironment = new Nunjucks.Environment(
//   new Nunjucks.FileSystemLoader('./site/_includes')
// );

module.exports = function (eleventyConfig) {
  const {Liquid} = require('liquidjs');
  let options = {
    extname: ".liquid",
    dynamicPartials: true,
    strictFilters: false, // renamed from `strict_filters` in Eleventy 1.0
    root: ["site/_includes"]
  };

  // eleventyConfig.setLibrary("liquid", new Liquid(options));
  // eleventyConfig.setLibrary('njk', nunjucksEnvironment);
  // eleventyConfig.setDataDeepMerge(true);
  eleventyConfig.addPassthroughCopy({ "site/images": "images" });
  eleventyConfig.addPassthroughCopy({ "site/dist": "dist" });

  eleventyConfig.addNunjucksAsyncShortcode("image", imageShortcode);
  eleventyConfig.addLiquidShortcode("image", imageShortcode);
  eleventyConfig.addJavaScriptFunction("image", imageShortcode);
    // eleventyConfig.addPairedShortcode("codepen", codepenIt);
  eleventyConfig.addShortcode("img", function(name, twitterUsername) {
    return `<svg style="width: 100%; height: auto;" width="469.3" height="91.601" viewBox="0 0 469.3 91.601" xmlns="http://www.w3.org/2000/svg"><g id="svgGroup" stroke-linecap="round" fill-rule="evenodd" font-size="9pt" stroke="#fff" stroke-width="0.25mm" fill="none" style="stroke:#fff;stroke-width:0.25mm;fill:none"><path d="M 25.7 21.601 L 38 21.601 L 38 32.801 L 25.7 32.801 L 25.7 54.201 A 11.62 11.62 0 0 0 25.989 56.918 Q 26.868 60.557 30.413 61.242 A 9.431 9.431 0 0 0 32.2 61.401 A 15.649 15.649 0 0 0 34.521 61.239 Q 36.722 60.909 38.2 59.901 L 41.2 72.101 Q 36.3 74.201 26.9 74.201 A 41.857 41.857 0 0 1 21.657 73.898 Q 14.34 72.97 11.4 69.201 Q 9.7 67.001 9.2 64.401 Q 8.7 61.801 8.7 55.801 L 8.7 32.801 L 0 32.801 L 0 21.601 L 8.7 21.601 L 8.7 10.601 L 25.7 1.701 L 25.7 21.601 Z" id="0" vector-effect="non-scaling-stroke"/><path d="M 44.3 21.601 L 61.3 21.601 L 61.3 73.501 L 44.3 73.501 L 44.3 21.601 Z M 58.291 2.242 A 7.588 7.588 0 0 0 52.8 0.001 A 9.61 9.61 0 0 0 52.697 0.001 A 7.525 7.525 0 0 0 47.25 2.251 A 7.561 7.561 0 0 0 45.598 4.677 A 7.978 7.978 0 0 0 45 7.801 A 9.762 9.762 0 0 0 45 7.807 A 7.589 7.589 0 0 0 47.25 13.301 A 7.588 7.588 0 0 0 49.547 14.932 A 7.784 7.784 0 0 0 52.8 15.601 A 7.778 7.778 0 0 0 55.263 15.217 A 7.696 7.696 0 0 0 58.3 13.301 A 9.286 9.286 0 0 0 58.371 13.229 A 7.493 7.493 0 0 0 60.6 7.801 Q 60.6 4.501 58.3 2.251 A 8.96 8.96 0 0 0 58.291 2.242 Z" id="1" vector-effect="non-scaling-stroke"/><path d="M 73 21.601 L 89.4 21.601 L 89.4 28.501 A 19.714 19.714 0 0 1 103.732 20.335 A 25.998 25.998 0 0 1 106.4 20.201 A 24.639 24.639 0 0 1 112.319 20.866 A 15.378 15.378 0 0 1 121.6 27.101 A 14.798 14.798 0 0 1 123.365 30.854 Q 124.566 34.585 124.687 40.058 A 56.459 56.459 0 0 1 124.7 41.301 L 124.7 73.501 L 107.6 73.501 L 107.6 44.101 A 33.586 33.586 0 0 0 107.537 41.961 Q 107.41 39.98 107.028 38.668 A 7.27 7.27 0 0 0 106.8 38.001 A 5.876 5.876 0 0 0 102.622 34.444 Q 101.494 34.144 100.103 34.106 A 14.912 14.912 0 0 0 99.7 34.101 A 12.145 12.145 0 0 0 96.455 34.506 A 8.009 8.009 0 0 0 91.3 38.701 A 8.779 8.779 0 0 0 90.567 40.598 Q 90.047 42.524 90.004 45.293 A 32.638 32.638 0 0 0 90 45.801 L 90 73.501 L 73 73.501 L 73 21.601 Z" id="2" vector-effect="non-scaling-stroke"/><path d="M 167.3 21.601 L 184.7 21.601 L 165.9 74.001 Q 162.84 82.475 159.41 86.393 A 12.897 12.897 0 0 1 157.5 88.201 A 13.411 13.411 0 0 1 153.822 90.101 Q 149.54 91.601 142.7 91.601 A 60.148 60.148 0 0 1 137.6 91.371 Q 135.003 91.15 132.139 90.715 A 103.233 103.233 0 0 1 130.2 90.401 L 132.9 76.901 A 11.552 11.552 0 0 0 134.733 77.388 Q 135.678 77.564 136.783 77.641 A 26.225 26.225 0 0 0 138.6 77.701 A 20.383 20.383 0 0 0 140.887 77.583 Q 143.257 77.314 144.562 76.433 A 3.663 3.663 0 0 0 145.6 75.401 Q 147.2 73.101 147.2 70.301 Q 147.2 69.738 146.936 69.088 A 4.783 4.783 0 0 0 146.9 69.001 L 129.2 21.601 L 147.3 21.601 L 154.5 44.601 Q 155.495 47.893 156.608 53.237 A 231.794 231.794 0 0 1 157.3 56.701 Q 159.3 47.801 160.5 44.001 L 167.3 21.601 Z" id="3" vector-effect="non-scaling-stroke"/><path d="M 244.3 1.901 L 270.7 1.901 L 270.7 73.501 L 253 73.501 L 253 57.401 Q 253 41.901 253.9 19.001 Q 250.634 32.837 247.295 43.866 A 283.743 283.743 0 0 1 246.2 47.401 L 237.9 73.501 L 223.6 73.501 L 215.2 48.101 A 200.881 200.881 0 0 1 212.609 39.654 Q 211.35 35.234 210.048 30.07 A 494.247 494.247 0 0 1 207.4 19.001 Q 208.173 36.532 208.282 50.518 A 576.611 576.611 0 0 1 208.3 55.001 L 208.3 73.501 L 190.6 73.501 L 190.6 1.901 L 217.4 1.901 L 225.1 26.801 A 164.846 164.846 0 0 1 228.247 38.464 Q 229.653 44.499 230.825 51.264 A 266.208 266.208 0 0 1 230.9 51.701 A 527.902 527.902 0 0 1 232.519 44.119 Q 234.435 35.474 236.169 29.269 A 150.046 150.046 0 0 1 237 26.401 L 244.3 1.901 Z" id="4" vector-effect="non-scaling-stroke"/><path d="M 330.7 45.601 L 349.4 45.601 Q 348.7 56.801 341.7 64.901 A 28.887 28.887 0 0 1 324.98 74.478 A 43.476 43.476 0 0 1 316.3 75.301 A 40.715 40.715 0 0 1 304.67 73.725 A 30.797 30.797 0 0 1 289 63.101 A 37.119 37.119 0 0 1 281.219 44.894 A 49.634 49.634 0 0 1 280.7 37.601 A 42.849 42.849 0 0 1 282.77 24.033 A 36.428 36.428 0 0 1 290.2 11.201 A 32.598 32.598 0 0 1 311.329 0.429 A 45.924 45.924 0 0 1 316.9 0.101 A 37.043 37.043 0 0 1 328.53 1.856 A 31.14 31.14 0 0 1 340.2 8.801 Q 348.2 16.301 348.9 27.201 L 330.7 27.201 A 24.199 24.199 0 0 0 329.849 24.244 Q 329.316 22.776 328.63 21.614 A 10.695 10.695 0 0 0 327.3 19.801 A 12.829 12.829 0 0 0 319.676 15.745 A 18.588 18.588 0 0 0 316.6 15.501 A 16.783 16.783 0 0 0 310.617 16.523 Q 306.403 18.118 303.6 22.201 A 21.871 21.871 0 0 0 300.737 28.784 Q 299.992 31.635 299.782 34.994 A 43.471 43.471 0 0 0 299.7 37.701 A 39.588 39.588 0 0 0 300.188 44.13 Q 300.759 47.591 301.997 50.358 A 18.101 18.101 0 0 0 304.7 54.701 A 14.326 14.326 0 0 0 315.167 59.862 A 19.496 19.496 0 0 0 316.4 59.901 Q 323.9 59.901 327.8 54.401 Q 329.879 51.431 330.684 45.717 A 40.728 40.728 0 0 0 330.7 45.601 Z" id="5" vector-effect="non-scaling-stroke"/><path d="M 358.4 1.901 L 415.7 1.901 L 415.7 17.301 L 377.1 17.301 L 377.1 29.201 L 412.4 29.201 L 412.4 43.901 L 377.1 43.901 L 377.1 58.101 L 416.6 58.101 L 416.6 73.501 L 358.4 73.501 L 358.4 1.901 Z" id="6" vector-effect="non-scaling-stroke"/><path d="M 452.7 70.901 L 441.9 70.901 L 441.9 54.401 L 425.2 54.401 L 425.2 43.501 L 441.9 43.501 L 441.9 26.701 L 452.7 26.701 L 452.7 43.501 L 469.3 43.501 L 469.3 54.401 L 452.7 54.401 L 452.7 70.901 Z" id="7" vector-effect="non-scaling-stroke"/></g></svg>`;
  });

  // Filter source file names using a glob
  eleventyConfig.addCollection("docs", function (collection) {
    return collection.getFilteredByGlob(['site/docs/*.md']);
  });

  eleventyConfig.addPlugin(pluginSyntaxHighlight);
  eleventyConfig.addPlugin(eleventyNavigationPlugin);
  // eleventyConfig.addTransform("htmlmin", function (content, outputPath) {
  //   if (outputPath.endsWith(".html")) {
  //     let minified = htmlmin.minify(content, {
  //       useShortDoctype: true,
  //       removeComments: true,
  //       collapseWhitespace: true,
  //     });
  //     return minified;
  //   }
  //   return content;
  // });

  eleventyConfig.setLibrary(
    "md",
    require("markdown-it")({
      html: true,
      breaks: true,
      linkify: true,
    })
  .use(anchor, {
    permalink: anchor.permalink.headerLink(),
    permalinkClass: "direct-link",
    permalinkSymbol: "¶",
  })
  );

  return {
    pathPrefix: "/",
    passthroughFileCopy: true,
    dir: {
      data: `_data`,
      input: 'site',
      includes: `_includes`,
      layouts: `_includes`,
      output: '_site',
    },
  };
};
