export default {
    formatBytes (bytes, decimals = 2) {
        bytes = parseInt(bytes, 10);
        const sizes = "B KB MB GB TB PB EB".split(" "), base = 1024;

        const ndx = Math.floor(Math.log(bytes) / Math.log(base));
        return parseFloat((bytes / Math.pow(base, ndx)).toFixed(decimals)) + ' ' + sizes[ndx];
    }
}