import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/contract.dart';
import '../models/milestone.dart';
import '../services/contract_service.dart';

class ContractListNotifier extends StateNotifier<AsyncValue<List<Contract>>> {
  ContractListNotifier(this.service, this.role) : super(const AsyncValue.loading());
  final ContractService service;
  final String role;

  Future<void> load() async {
    state = const AsyncValue.loading();
    try {
      state = AsyncValue.data(await service.listContracts(role));
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }
}

class ContractDetailNotifier extends StateNotifier<AsyncValue<Contract?>> {
  ContractDetailNotifier(this.service) : super(const AsyncValue.loading());
  final ContractService service;

  Future<void> fetch(int id) async {
    state = const AsyncValue.loading();
    try {
      state = AsyncValue.data(await service.fetchContract(id));
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<void> action(int id, Milestone milestone, String action) async {
    await service.updateMilestone(id, milestone, action);
  }
}
