class Editor {
    mapResultOfArticles(articles) {
        return articles.map(item => {
            let domain = (item.domain !== null) ? item.domain.toLowerCase().trim() : '';
            let title = (item.doc_title !== null) ? item.doc_title.toLowerCase().trim() : '';
            let keyword = (item.keyword !== null) ? item.keyword.toLowerCase().trim() : '';
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
                domain_protected_copy: (item.domain_protected !== null && item.domain_protected.length > 50) ? item.domain_protected.substr(0, 50) + '...' : item.domain_protected,
                firstname: item.firstname,
                hr_spent_editor_edit_article: item.hr_spent_editor_edit_article,
                id: item.word_id,
                isCsCheckHitMax: item.isCsCheckHitMax,
                csCounter: item.csCounter,
                isEditorEdit: item.isEditorEdit,
                isEditorUpdateSC: item.isEditorUpdateSC,
                isRespinHitMax: item.isRespinHitMax,
                respinCounter: item.respinCounter,
                isProcess: item.isProcess,
                keyword: keyword,
                lastname: item.lastname,
                lsi_terms: item.lsi_terms,
                lsi_terms_copy: (item.lsi_terms !== null && item.lsi_terms.length > 50) ? item.lsi_terms.substr(0, 50) + '...' : item.lsi_terms,
                min_spent_editor_edit_article: item.min_spent_editor_edit_article,
                protected: item.protected,
                protected_copy: (item.protected !== null && item.protected.length > 50) ? item.protected.substr(0, 50) + '...' : item.protected,
                sec_spent_editor_edit_article: item.sec_spent_editor_edit_article,
                spin: spin,
                spintax: spintax,
                spintax_copy: spintax_copy,
                synonym: item.synonym,
                citation: item.citation,
                writer: item.firstname + ' ' + item.lastname,
                filename: filename,
                file: filename + '.txt',
                srcPath: '/downloads/' + filename + '.txt'
            };
        });
    }
}

export default Editor;