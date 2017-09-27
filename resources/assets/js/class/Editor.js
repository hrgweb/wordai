class Editor
{
    mapResultOfArticles(articles) {
        return articles.map(item => {
            let domain = item.domain.length > 0 ? item.domain.toLowerCase().trim() : '';
            let title = item.doc_title.length > 0 ? item.doc_title.toLowerCase().trim() : '';
            let keyword = item.keyword.length > 0 ? item.keyword.toLowerCase().trim() : '';
            let spin = item.spin !== null ? item.spin : '';
            let spintax = item.spintax !== null ? item.spintax : '';
            let spintax_copy = item.spintax_copy !== null ? item.spintax_copy : '';
            let filename = domain + '-' + title + '-' + keyword;

            return {
                article: item.article,
                article_type: item.article_type,
                created_at: item.created_at,
                doc_title: (title !== null && title.length > 50) ? title.substr(0, 50) + '...' : title,
                domain: domain,
                domain_protected: item.domain_protected,
                firstname: item.firstname,
                hr_spent_editor_edit_article: item.hr_spent_editor_edit_article,
                id: item.word_id,
                isCsCheckHitMax: item.isCsCheckHitMax,
                isEditorEdit: item.isEditorEdit,
                isEditorUpdateSC: item.isEditorUpdateSC,
                isRespinHitMax: item.isRespinHitMax,
                isProcess: item.isProcess,
                keyword: keyword,
                lastname: item.lastname,
                lsi_terms: item.lsi_terms,
                min_spent_editor_edit_article: item.min_spent_editor_edit_article,
                protected: (item.protected !== null && item.protected.length > 100) ? item.protected.substr(0, 100) + '...' : item.protected,
                sec_spent_editor_edit_article: item.sec_spent_editor_edit_article,
                spin: spin,
                spintax: spintax,
                spintax_copy: spintax_copy,
                synonym: item.synonym,
                writer: item.firstname + ' ' + item.lastname,
                filename: filename,
                file: filename + '.txt',
                srcPath: '/downloads/' + filename + '.txt'
            };
        });
    }
}

export default Editor;