import 'package:flutter/foundation.dart';

import '../models/billing.dart';
import '../services/billing_service.dart';

class BillingState extends ChangeNotifier {
  BillingState(this.service);

  final BillingService service;
  BillingPlan? currentPlan;
  List<BillingPlan> plans = <BillingPlan>[];
  List<InvoiceSummary> invoices = <InvoiceSummary>[];

  Future<void> load() async {
    currentPlan = await service.fetchCurrentPlan();
    plans = await service.fetchPlans();
    invoices = await service.fetchInvoices();
    notifyListeners();
  }

  Future<void> choosePlan(int planId) async {
    await service.choosePlan(planId);
    await load();
  }

  Future<void> buyCredits(int quantity) async {
    await service.buyCredits(quantity);
    await load();
  }
}
