class AnalyticsMetric {
  final String key;
  final num value;
  final String? label;

  const AnalyticsMetric({
    required this.key,
    required this.value,
    this.label,
  });

  factory AnalyticsMetric.fromJson(Map<String, dynamic> json) {
    return AnalyticsMetric(
      key: json['key'] as String? ?? '',
      value: json['value'] as num? ?? 0,
      label: json['label'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'key': key,
        'value': value,
        if (label != null) 'label': label,
      };
}

class AnalyticsChartData {
  final DateTime timestamp;
  final num value;
  final Map<String, dynamic>? meta;

  const AnalyticsChartData({
    required this.timestamp,
    required this.value,
    this.meta,
  });

  factory AnalyticsChartData.fromJson(Map<String, dynamic> json) {
    return AnalyticsChartData(
      timestamp: DateTime.tryParse(json['timestamp'].toString()) ?? DateTime.now(),
      value: json['value'] as num? ?? 0,
      meta: json['meta'] as Map<String, dynamic>?,
    );
  }

  Map<String, dynamic> toJson() => {
        'timestamp': timestamp.toIso8601String(),
        'value': value,
        if (meta != null) 'meta': meta,
      };
}

class AnalyticsSeries {
  final String metric;
  final List<AnalyticsChartData> points;

  const AnalyticsSeries({
    required this.metric,
    this.points = const [],
  });

  factory AnalyticsSeries.fromJson(Map<String, dynamic> json) {
    final data = (json['data'] as Map<String, dynamic>?) ?? {};
    final points = <AnalyticsChartData>[];
    data.forEach((key, value) {
      points.add(AnalyticsChartData(
        timestamp: DateTime.tryParse(key) ?? DateTime.now(),
        value: (value as num?) ?? 0,
      ));
    });
    return AnalyticsSeries(
      metric: json['metric'] as String? ?? json['name'] as String? ?? 'events',
      points: points,
    );
  }

  Map<String, dynamic> toJson() => {
        'metric': metric,
        'points': points.map((e) => e.toJson()).toList(),
      };
}
