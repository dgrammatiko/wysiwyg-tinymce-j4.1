
const pakage = require('../../package.json');

module.exports = () => {
  return {
    url: 'https://wysiwyg.dgrammatiko.dev',
    repo: pakage.data.repo,
    version: pakage.version,
    title: 'True WYSIWYG for the tinyMCE editor',
    sha256: pakage.data.sha256,
    sha384: pakage.data.sha384,
    sha512: pakage.data.sha512,
  };
}
