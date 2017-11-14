import { Component } from '@angular/core';
import { SubmitService } from './submit.service';
@Component({
	selector: 'build003',
	templateUrl: './build003.component.html',
	styleUrls: ['./build003.component.scss']
})
export class Build003Component {
	submit_name = '';
	submit_email = '';
	submit_phone = '';
	submit_message = '';
	submit_av_lang = '';
	submit_dev_or_design = '';
	submit_career = '';
	submit_age;
	constructor(private submitService: SubmitService) {}
	ngAfterViewInit() {
		// scroll to top
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}
	ngOnInit() {}
	addSubmit(submit_name, submit_email, submit_dev_or_design, submit_av_lang, submit_career, submit_age, submit_phone, submit_message) {
		this.submitService.create(submit_name, submit_email, submit_dev_or_design, submit_av_lang, submit_career, submit_age, submit_phone, submit_message);
	}
}
