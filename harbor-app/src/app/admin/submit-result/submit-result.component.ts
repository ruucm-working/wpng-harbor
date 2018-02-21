import { Component } from '@angular/core';
import { SubmitService } from '../../shared/services/submit.service';
// import { PaginationService } from 'ngx-pagination';
import { log } from 'ruucm-util';
interface Pagination {
	limit: number;
	skip: number;
}
interface Options extends Pagination {
	[key: string]: any
}
@Component({
	selector: 'submit-result',
	templateUrl: './submit-result.component.html',
	styleUrls: ['./submit-result.component.scss']
})
export class SubmitResultComponent {
	submit_results;
	// dressDetails;
	// etcDetails;
	// search_keyword = '';
	// p: number = 1;
	constructor(
		private submitresultService: SubmitService,
		// private paginationService: PaginationService
	) {}
	ngOnInit() {
		this.submit_results = this.submitresultService.getSnapshot();
		this.submit_results.subscribe(submit_result => {
			log('submit_result', submit_result);
		});
	}
	complete_toggle(result) {
		if (result.complete == null || result.complete == 'uncomplete')
			this.submitresultService.updateSubmit(result.id, { complete: 'complete' })
		else
			this.submitresultService.updateSubmit(result.id, { complete: 'uncomplete' })

	}	// getDressDetails(source, key) {
	// 	var arr = [];
	// 	if (key) {
	// 		for (var i = 0; i < key.length; ++i) {
	// 			arr.push(source['dress-types-' + key[i].slug])
	// 		}
	// 	}
	// 	return arr;
	// }
	// getEtcDetails(source, key) {
	// 	var arr = [];
	// 	if (key) {
	// 		for (var i = 0; i < key.length; ++i) {
	// 			var main_key = 'dress-types-' + key[i].slug;
	// 			if (i >= 1) arr.push('/');
	// 			for(var k in source) {
	// 				if(k.indexOf(main_key) == 0) {
	// 					arr.push(source[k]);
	// 				}
	// 			}
	// 		}
	// 	}
	// 	return arr;
	// }
}
