<template>
	<div class="Copyscape">
		<div class="Table">
			<h3>Copyscape Table</h3>

			<table cellpadding="6" border="0" width="100%">
				<tbody>
				<tr bgcolor="#DDDDDD">	
					<td><b>Parameter</b></td>
					<td><b>Explanation</b></td>
					<td><b>Value</b></td>
					<td><b>Required?</b></td>
					<td><b>Default</b></td>
				</tr>
				<tr>
					<td><b>o</b></td>
					<td>API operation</td>
					<td><b>csearch</b> (or <b>psearch</b> or <b>cpsearch</b><br>if you create a private index)</td>
					<td>Yes</td>
					<td>-</td>
				</tr>
				<tr>
					<td><b>e</b></td>
					<td>Text encoding</td>
					<td><i>[encoding name]</i></td>
					<td>Yes</td>
					<td>-</td>
				</tr>
				<tr>
					<td><b>c</b></td>
					<td>Full comparisons</td>
					<td><b>0</b> to <b>10</b></td>
					<td>No</td>
					<td><b>0</b></td>
				</tr>
				<tr>
					<td><b>i</b></td>
					<td>Ignore sites</td>
					<td><i>[comma-delimited domains to ignore]</i></td>
					<td>No</td>
					<td>-</td>
				</tr>
				<tr>
					<td><b>x</b></td>
					<td>Example test</td>
					<td><b>1</b> or omitted</td>
					<td>No</td>
					<td>-</td>
				</tr>
				</tbody>
			</table><br>
		</div>

		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<div class="Params">
				<h3>Custom Parameters</h3>

				<form method="POST">
					<div class="form-group">
						<label for="o">o</label> &nbsp;
						<select v-model="copyscape.o">
							<option v-for="o in o" :value="o">{{ o }}</option>
						</select>
					</div>
				
					<div class="form-group">
						<label for="e">e</label> &nbsp;
						<select v-model="copyscape.e">
							<option v-for="e in e" :value="e">{{ e }}</option>
						</select>
					</div>

					<div class="form-group">
						<label for="c">c</label> &nbsp;
						<select v-model="copyscape.c">
							<option :value="0">0</option>
							<option v-for="n in 10" :value="n">{{ n }}</option>
						</select>
					</div>

					<div class="form-group">
						<label for="i">i</label> &nbsp;
						<span style="color: red;"> &nbsp; <b>Reminder</b>: seperate with comma "," e.g www.site1.com, www.site2.com</span>
						<textarea class="form-control" rows="6" v-model="copyscape.i"></textarea><br>

						<div class="ignored-sites" v-if="! isIgnoreSitesEmpty">
							<b>Ignored Sites:</b>
							<pre>{{ listIgnoreSites }}</pre>
						</div>
					</div>

					<div class="form-group">
						<label for="x">x</label> &nbsp;
						<select v-model="copyscape.x">
							<option v-for="x in x" :value="x">{{ x }}</option>
						</select>
					</div>
				
					<button type="submit" class="btn btn-primary" @click.prevent="setCsSetting">Save New Settings</button>
				</form>
			</div>
		</div>

		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<div class="Docs">
				<h3>Documentation</h3>

				<p>
				    <small>API operation (<b>o</b>): Use <b>csearch</b> to search against the public Internet or <b>psearch</b> to search against your private index. You can also use <b>cpsearch</b> to search against both the Internet and your private index, for the cost of two search credits.</small>
				</p>
				<p>
				    <small>Text encoding (<b>e</b>): Use an <a href="http://www.iana.org/assignments/character-sets">IANA name</a>, such as <b>UTF-8</b> (Unicode), <b>ISO-8859-1</b> (Latin-1) or <b>WINDOWS-1251</b> (Cyrillic).</small>
				</p>
				<p><small>
					Full comparisons (<b>c</b>): Set to a value between <b>1</b> and <b>10</b> to request a full text-on-text comparison (with an exact count of matching words) between the query text and the top (one to ten) results found. Note that full comparisons may add a delay of a few seconds.
				</small></p>
				<p><small>
					Ignore sites (<b>i</b>): Subdomains are also omitted from the results. For example, if set to <b>site1.com,site2.com</b> then <b>www.site1.com</b> and <b>blog.site2.com</b> would also be ignored. Ignore sites listed in your user settings are always applied. 
				</small></p>
				<p><small>
					Example test (<b>x</b>): If set to <b>1</b>, the API will search the Internet for copies of the text on <a href="http://www.copyscape.com/example.html">this page</a> and you will not be charged.
				</small>
				</p>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				o: ['csearch', 'psearch'],
				e: ['UTF-8', 'ISO-8859-1', 'WINDOWS-1251'],
				i: '',
				x: ['', 1],
				copyscape: {},
				listIgnoreSites: [],
				isIgnoreSitesEmpty: true
			}
		},
		watch: {
			listIgnoreSites(data) {
				this.isIgnoreSitesEmpty = data.length <= 0 ? true : false;
			}
		},
		created() {
			this.retrieveCopyscapeSetting();
		},
		methods: {
			ignoreSites() {
				let result = this.copyscape.i.split(/\,/g);

				return result.map(item => {
					let newArr = '';

					newArr += item.toLowerCase().trim();

					return newArr;
				})
			},

			setCsSetting() {
				axios.patch('/admin/updateCopyscapeSetting', this.copyscape).then(response => {
					if (response.data) {
						this.listIgnoreSites = this.ignoreSites();
						
						// notify user new settings updates successfully
						new Noty({
							type: 'success',
							text: `New settings for copyscape updated successfully.`,
							layout: 'bottomLeft',
							timeout: 5000
						}).show();
					}
				});
			},

			retrieveCopyscapeSetting() {
				axios.get('/admin/retrieveCopyscapeSetting').then(response => {
					let data = response.data;

					this.copyscape = data;
					if (data.i.length > 0) this.listIgnoreSites = this.ignoreSites();
				});
			}
		}
	}
</script>

<style scoped>
	table td { padding: .5em 1em; }

	.Table, .Params { margin-bottom: 3em; }
</style>