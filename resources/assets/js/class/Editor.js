class Editor
{
    mapResultOfArticles(articles) {
        return articles.map(item => {
            return {
                article: item.article,
                article_type: item.article_type,
                created_at: item.created_at,
                doc_title: (item.doc_title !== null && item.doc_title.length > 50) ? item.doc_title.substr(0, 50) + '...' : item.doc_title,
                domain: item.domain,
                domain_protected: item.domain_protected,
                firstname: item.firstname,
                hr_spent_editor_edit_article: item.hr_spent_editor_edit_article,
                id: item.word_id,
                isCsCheckHitMax: item.isCsCheckHitMax,
                isEditorEdit: item.isEditorEdit,
                isEditorUpdateSC: item.isEditorUpdateSC,
                isRespinHitMax: item.isRespinHitMax,
                keyword: item.keyword,
                lastname: item.lastname,
                lsi_terms: item.lsi_terms,
                min_spent_editor_edit_article: item.min_spent_editor_edit_article,
                protected: (item.protected !== null && item.protected.length > 100) ? item.protected.substr(0, 100) + '...' : item.protected,
                sec_spent_editor_edit_article: item.sec_spent_editor_edit_article,
                spin: item.spin,
                spintax: item.spintax,
                spintax_copy: item.spintax_copy,
                synonym: item.synonym,
                writer: item.firstname + ' ' + item.lastname
            };
        });
    }
}

export default Editor;