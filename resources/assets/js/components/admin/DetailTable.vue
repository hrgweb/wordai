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
		            	<button type="button" class="btn btn-danger" @click="removeDetails(detail, index)">Remove</button>
		            </td>
		        </tr>
		    </tbody>
		</table>
	</div>
</template>

<script>
	export default {
		props: ['details'],
		methods: {
			editDetails(detail, index) {
				this.$emit('isEdited', {
					detail: detail,
					index: index
				});
			},

			removeDetails(detail, index) {
				this.$emit('isRemoving', {
					id: detail.id,
					index: index
				});
			}
		}
	}
</script>