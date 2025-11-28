import 'dart:convert';

import 'package:http/http.dart' as http;
import '../models/models.dart';

typedef TokenProvider = Future<String?> Function();

class _Endpoints {
  static const advertisers = '/api/advertisement/advertisers';
  static const campaigns = '/api/advertisement/campaigns';
  static const creatives = '/api/advertisement/creatives';
  static const targeting = '/api/advertisement/campaigns';
  static const metrics = '/api/advertisement/campaigns';
  static const keywordPlanner = '/api/advertisement/keyword-planner';
  static const affiliateReferrals = '/api/advertisement/affiliates/referrals';
  static const affiliatePayouts = '/api/advertisement/affiliates/payouts';
}

class AdvertisementApiClient {
  AdvertisementApiClient({
    required this.baseUrl,
    required this.tokenProvider,
    http.Client? httpClient,
  }) : _client = httpClient ?? http.Client();

  final String baseUrl;
  final TokenProvider tokenProvider;
  final http.Client _client;

  Future<List<Campaign>> fetchCampaigns() async {
    final response = await _get(_Endpoints.campaigns);
    final decoded = jsonDecode(response.body) as Map<String, dynamic>;
    final data = decoded['data'] as List<dynamic>;
    return data.map((c) => Campaign.fromJson(c as Map<String, dynamic>)).toList();
  }

  Future<Campaign> createCampaign(Map<String, dynamic> payload) async {
    final response = await _post(_Endpoints.campaigns, payload);
    return Campaign.fromJson(jsonDecode(response.body) as Map<String, dynamic>);
  }

  Future<Campaign> updateCampaign(int id, Map<String, dynamic> payload) async {
    final response = await _put('${_Endpoints.campaigns}/$id', payload);
    return Campaign.fromJson(jsonDecode(response.body) as Map<String, dynamic>);
  }

  Future<List<Creative>> fetchCreatives({int? adGroupId, int? campaignId}) async {
    final query = <String, String>{
      if (adGroupId != null) 'ad_group_id': '$adGroupId',
      if (campaignId != null) 'campaign_id': '$campaignId',
    };
    final response = await _get(_Endpoints.creatives, query: query);
    final decoded = jsonDecode(response.body) as Map<String, dynamic>;
    final data = decoded['data'] as List<dynamic>;
    return data.map((c) => Creative.fromJson(c as Map<String, dynamic>)).toList();
  }

  Future<Creative> createCreative(Map<String, dynamic> payload) async {
    final response = await _post(_Endpoints.creatives, payload);
    return Creative.fromJson(jsonDecode(response.body) as Map<String, dynamic>);
  }

  Future<Creative> updateCreative(int id, Map<String, dynamic> payload) async {
    final response = await _put('${_Endpoints.creatives}/$id', payload);
    return Creative.fromJson(jsonDecode(response.body) as Map<String, dynamic>);
  }

  Future<List<Metric>> fetchMetrics({
    required int campaignId,
    required DateTime start,
    required DateTime end,
  }) async {
    final response = await _get(
      '${_Endpoints.metrics}/$campaignId/reports',
      query: {
        'from': start.toIso8601String(),
        'to': end.toIso8601String(),
      },
    );
    final decoded = jsonDecode(response.body) as Map<String, dynamic>;
    final data = decoded['timeseries'] as List<dynamic>;
    return data.map((m) => Metric.fromJson(m as Map<String, dynamic>)).toList();
  }

  Future<Forecast> createForecast(int campaignId, Map<String, dynamic> payload) async {
    final response = await _post('${_Endpoints.campaigns}/$campaignId/forecast', payload);
    return Forecast.fromJson(jsonDecode(response.body) as Map<String, dynamic>);
  }

  Future<List<KeywordPrice>> keywordPrices(List<String> keywords) async {
    final response = await _post(_Endpoints.keywordPlanner, {'keywords': keywords});
    final data = jsonDecode(response.body) as List<dynamic>;
    return data.map((k) => KeywordPrice.fromJson(k as Map<String, dynamic>)).toList();
  }

  Future<List<AffiliateReferral>> referrals() async {
    final response = await _get(_Endpoints.affiliateReferrals);
    final decoded = jsonDecode(response.body) as Map<String, dynamic>;
    final data = decoded['data'] as List<dynamic>;
    return data.map((r) => AffiliateReferral.fromJson(r as Map<String, dynamic>)).toList();
  }

  Future<AffiliateReferral> createReferral(Map<String, dynamic> payload) async {
    final response = await _post(_Endpoints.affiliateReferrals, payload);
    return AffiliateReferral.fromJson(jsonDecode(response.body) as Map<String, dynamic>);
  }

  Future<List<AffiliatePayout>> payouts() async {
    final response = await _get(_Endpoints.affiliatePayouts);
    final decoded = jsonDecode(response.body) as Map<String, dynamic>;
    final data = decoded['data'] as List<dynamic>;
    return data.map((p) => AffiliatePayout.fromJson(p as Map<String, dynamic>)).toList();
  }

  Future<http.Response> _get(String path, {Map<String, String>? query}) async {
    final uri = Uri.parse('$baseUrl$path').replace(queryParameters: query);
    final headers = await _headers();
    final response = await _client.get(uri, headers: headers);
    _throwOnError(response);
    return response;
  }

  Future<http.Response> _post(String path, Map<String, dynamic> payload) async {
    final uri = Uri.parse('$baseUrl$path');
    final headers = await _headers();
    final response = await _client.post(uri, headers: headers, body: jsonEncode(payload));
    _throwOnError(response);
    return response;
  }

  Future<http.Response> _put(String path, Map<String, dynamic> payload) async {
    final uri = Uri.parse('$baseUrl$path');
    final headers = await _headers();
    final response = await _client.put(uri, headers: headers, body: jsonEncode(payload));
    _throwOnError(response);
    return response;
  }

  Future<Map<String, String>> _headers() async {
    final token = await tokenProvider();
    return {
      'Content-Type': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  void _throwOnError(http.Response response) {
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return;
    }
    throw ApiException(response.statusCode, response.body);
  }
}

class ApiException implements Exception {
  ApiException(this.statusCode, this.body);
  final int statusCode;
  final String body;

  @override
  String toString() => 'ApiException($statusCode): $body';
}
