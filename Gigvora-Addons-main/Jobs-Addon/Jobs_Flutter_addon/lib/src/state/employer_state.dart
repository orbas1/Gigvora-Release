import 'package:flutter/foundation.dart';

import '../models/job.dart';
import '../services/employer_service.dart';

class EmployerState extends ChangeNotifier {
  EmployerState(this.service);

  final EmployerService service;
  Map<String, int> kpis = {};
  List<Job> jobs = <Job>[];
  bool loading = false;

  Future<void> loadDashboard() async {
    loading = true;
    notifyListeners();
    kpis = await service.fetchKpis();
    loading = false;
    notifyListeners();
  }

  Future<void> loadJobs({String? status}) async {
    jobs = await service.fetchJobs(status: status);
    notifyListeners();
  }
}
