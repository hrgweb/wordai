<template>
	<div class="DetailTable">
		<table class="table table-striped table-hover">
		    <thead>
		        <tr>
		            <th>Domain</th>
		            <th>Protected Terms</th>
		            <th>Synonyms</th>
		            <th>Date Added</th>
		            <th class="text-center">Actions</th>
		        </tr>
		    </thead>
		    <tbody>
		        <tr v-for="(detail, index) in details">
		            <td>{{ detail.domain }}</td>
		            <td>{{ detail.protected }}</td>
		            <td>{{ detail.synonym }}</td>
		            <td>{{ detail.created_at }}</td>
		            <td>
		            	<button type="button" class="btn btn-info" @click="editDetails(detail, index)">Edit</button>
		            	<button
                            type="button"
                            class="btn btn-danger"
                            @click="removeDetails(detail, index)"
                        >
                            Remove
                        </button>
                        <!-- <button
                            type="button"
                            class="btn btn-danger"
                            v-if="domainBus.isAdmin"
                            @click="removeDetails(detail, index)"
                        >
                            Remove
                        </button> -->
		            </td>
		        </tr>
		    </tbody>
		</table>
	</div>
</template>

<script>
	export default {
		props: ['details'],
        data() {
            return { domainBus: DomainBus };
        },
		methods: {
			editDetails(detail, index, event) {
                let url = '/admin/editDetails?detail_id='+detail.id+'&group_id='+detail.group_id;

                axios.get(url).then(response => {
                    let data = response.data;

                    this.$emit('isEdited', {
                        detail: data,
                        index: index
                    });
                });
			},

			removeDetails(detail, index) {
				this.$emit('isRemoving', {
					detail: detail,
					index: index
				});
			}
		}
	}
</script>