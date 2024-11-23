(function(wp) {
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginPrePublishPanel = wp.editPost.PluginPrePublishPanel;
    var __ = wp.i18n.__;
    var createElement = wp.element.createElement;
    var useEffect = wp.element.useEffect;
    var dispatch = wp.data.dispatch;

var CustomNoticePlugin = function() {
    useEffect(function() {
        var noticeContent = 'To add/edit the incident updates visit <a href="/wp-admin/admin.php?page=updates">the Updates section</a> to edit the content, not this page.';

        dispatch('core/notices').createNotice(
            'info',
            noticeContent,
            {
                isDismissible: false,
                className: 'custom-editor-notice',
                __unstableHTML: true // This allows HTML in the notice
            }
        );
    }, []);



  

        return createElement(
            PluginPrePublishPanel,
            {
                className: 'custom-editor-notice-panel',
                title: __('Important Notice'),
                initialOpen: true,
            },
            createElement(
                'p',
                {},
                __('To add/edit the incident updates visit the '),
                createElement(
                    'a',
                    { href: '/wp-admin/admin.php?page=updates' },
                    __('Updates section')
                ),
                __(' to edit the content, not this page.')
            )
        );
    }

    registerPlugin('custom-editor-notice-plugin', {
        render: CustomNoticePlugin
    });
})(window.wp);
