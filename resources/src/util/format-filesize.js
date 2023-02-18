function formatFilesize (size, sep) {
    let i = Math.floor(Math.floor(Math.log(size) / Math.log(1000)));
    return (size / Math.pow(1000, i)).toFixed(i ? 2 : 0).toString().replace('.', sep || '.') + ['B', 'kB', 'MB', 'GB'][i];
}

export { formatFilesize }
