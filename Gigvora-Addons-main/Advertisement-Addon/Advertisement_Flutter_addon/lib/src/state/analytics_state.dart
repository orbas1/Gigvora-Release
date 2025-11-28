part of 'ads_blocs.dart';

class AnalyticsState extends Equatable {
  const AnalyticsState._({
    this.metrics = const [],
    this.status = AnalyticsStatus.idle,
    this.error,
  });

  const AnalyticsState.idle() : this._(status: AnalyticsStatus.idle);
  const AnalyticsState.loading() : this._(status: AnalyticsStatus.loading);
  const AnalyticsState.ready(List<Metric> metrics)
      : this._(status: AnalyticsStatus.ready, metrics: metrics);
  const AnalyticsState.error(String message)
      : this._(status: AnalyticsStatus.error, error: message);

  final List<Metric> metrics;
  final AnalyticsStatus status;
  final String? error;

  @override
  List<Object?> get props => [metrics, status, error];
}

enum AnalyticsStatus { idle, loading, ready, error }
