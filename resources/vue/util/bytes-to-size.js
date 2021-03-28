export default {
    formatBytes (bytes, decimals = 2, base = 1024) {
        let i = Math.floor(Math.log(bytes) / Math.log(base));
        return (bytes /Math.pow(base, i)).toFixed(decimals) + " " + ("KMGTPEZY"[i-1] || "") + "B";
    }
}