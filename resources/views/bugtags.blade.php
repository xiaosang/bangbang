@if (config('app.debug'))
    <script src="https://dn-bts.qbox.me/sdk/bugtags-1.0.3.js"></script>
    <script>
        // VERSION_NAME 替换为项目的版本，VERSION_CODE 替换为项目的子版本
        new Bugtags('82dfb118f2f76dd2ad2e8b760d2435f6', '0.1.0', '2');
    </script>
@endif