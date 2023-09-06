interface SaleMap {
    saleNum: number;
    areaName: string;
    hasTrade: number;
    __typename: string;
}

interface SalePie {
    first_cid: number;
    sale_today: string;
    sale_yesterday: string;
    sale_three_days: string;
    sale_seven_days: string;
    sale_fourteen_days: string;
    sale_thirty_days: string;
    sale_sixty_days: string;
    today_money: string;
    yesterday_money: string;
    three_days_money: string;
    seven_days_money: string;
    fourteen_days_money: string;
    thirty_days_money: string;
    sixty_days_money: string;
    name: string;
}

interface IHomeData {
    salePie: SalePie[];
    saleMap: SaleMap[];
}

type itmeList = Array<item>

interface item{
	title:string
	icon:string
	index:string
	path?:string
}

interface leftobj{
	title: string,
	icon: any,
	index: string,
	itemList: itmeList
}
