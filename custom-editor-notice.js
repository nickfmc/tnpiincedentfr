(function(wp) {
    var registerPlugin = wp.plugins.registerPlugin;
    var PluginPrePublishPanel = wp.editPost.PluginPrePublishPanel;
    var __ = wp.i18n.__;
    var createElement = wp.element.createElement;
    var useEffect = wp.element.useEffect;
    var dispatch = wp.data.dispatch;

var CustomNoticePlugin = function() {
    useEffect(function() {
    var noticeContent = 'Pour ajouter/modifier les mises à jour des incidents <a href="/wp-admin/admin.php?page=updates">cliquez ici.</a> Le contenu n\'est pas directement modifiable sur cette page.';

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
             __('Pour ajouter/modifier les mises à jour des incidents, visitez '),
             createElement(
                 'a',
                 { href: '/wp-admin/admin.php?page=updates' },
                 __('mises à jour')
             ),
             __(' pour modifier le contenu, pas cette page.')
         )
         
        );
    }

    registerPlugin('custom-editor-notice-plugin', {
        render: CustomNoticePlugin
    });
})(window.wp);
