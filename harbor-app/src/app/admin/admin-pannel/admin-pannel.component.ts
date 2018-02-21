import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../core/auth.service';
@Component({
	selector: 'admin-pannel',
	templateUrl: './admin-pannel.component.html',
	styleUrls: ['./admin-pannel.component.scss']
})
export class AdminPannelComponent implements OnInit {
	constructor(public auth: AuthService) {}
	ngOnInit() {}
}
