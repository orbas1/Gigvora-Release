import 'package:flutter_bloc/flutter_bloc.dart';

import '../models/models.dart';
import '../services/ads_service.dart';

enum KeywordPlannerStatus { idle, loading, loaded, error }

class KeywordPlannerState {
  const KeywordPlannerState({
    this.status = KeywordPlannerStatus.idle,
    this.results = const [],
    this.error,
  });

  final KeywordPlannerStatus status;
  final List<KeywordPrice> results;
  final String? error;

  KeywordPlannerState copyWith({
    KeywordPlannerStatus? status,
    List<KeywordPrice>? results,
    String? error,
  }) {
    return KeywordPlannerState(
      status: status ?? this.status,
      results: results ?? this.results,
      error: error ?? this.error,
    );
  }
}

class KeywordPlannerCubit extends Cubit<KeywordPlannerState> {
  KeywordPlannerCubit(this.service) : super(const KeywordPlannerState());

  final AdsService service;

  Future<void> search(String query) async {
    emit(state.copyWith(status: KeywordPlannerStatus.loading));
    try {
      final results = await service.keywordIdeas([query]);
      emit(state.copyWith(status: KeywordPlannerStatus.loaded, results: results));
    } catch (e) {
      emit(state.copyWith(status: KeywordPlannerStatus.error, error: e.toString()));
    }
  }
}
